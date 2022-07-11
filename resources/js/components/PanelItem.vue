<template>
  <div
    class="flex flex-col md:flex-row -mx-6 px-6 py-2 md:py-0 space-y-2 md:space-y-0"
    :class="{
      'border-t border-gray-100 dark:border-gray-700': index !== 0,
      [field.size]: !!field.size,
      'w-full': !field.size,
    }"
    :dusk="field.attribute"
  >
    <div class="md:w-1/4 md:py-3">
      <slot>
        <h4 class="font-bold md:font-normal">
          <span>{{ label }}</span>
        </h4>
      </slot>
    </div>
    <div class="md:w-3/4 md:py-3 break-all lg:break-words" :class="{ 'w-full': !!field.size }">
      <slot name="value">
        <button
          v-if="fieldValue && field.copyable && !shouldDisplayAsHtml"
          @click.prevent="copy"
          type="button"
          class="flex items-center hover:bg-gray-50 dark:hover:bg-gray-900 text-gray-500 dark:text-gray-400 hover:text-gray-500 active:text-gray-600 rounded-lg px-1 -mx-1"
          v-tooltip="__('Copy to clipboard')"
        >
          <span ref="theFieldValue">
            {{ fieldValue }}
          </span>

          <Icon class="text-gray-500 dark:text-gray-400 ml-1" :solid="true" type="clipboard" width="14" />
        </button>

        <p v-else-if="fieldValue && !field.copyable && !shouldDisplayAsHtml" class="text-90 flex items-center">
          {{ fieldValue }}
        </p>
        <div v-else-if="fieldValue && !field.copyable && shouldDisplayAsHtml" v-html="field.value" />
        <p v-else>&mdash;</p>
      </slot>
    </div>
  </div>
</template>

<script>
import { CopiesToClipboard } from 'laravel-nova';

export default {
  mixins: [CopiesToClipboard],
  props: {
    index: {
      type: Number,
      required: true,
    },

    field: {
      type: Object,
      required: true,
    },

    fieldName: {
      type: String,
      default: '',
    },
  },

  mounted() {
    if (!!this.field.size) {
      this.$parent.$parent.$el.classList.add('flex-wrap');
      this.$parent.$parent.$el.classList.add('flex');
    }

    if (this.field.removeBottomBorder) {
      this.$el.children[0].classList.add('remove-bottom-border');
    } else {
      this.$el.children[0].classList.remove('remove-bottom-border');
    }
  },

  methods: {
    copy() {
      this.copyValueToClipboard(this.fieldValue);
    },
  },

  computed: {
    label() {
      return this.fieldName || this.field.name;
    },

    fieldValue() {
      if (this.field.value === '' || this.field.value === null || this.field.value === undefined) {
        return false;
      }

      return String(this.field.value);
    },

    shouldDisplayAsHtml() {
      return this.field.asHtml;
    },
  },
};
</script>

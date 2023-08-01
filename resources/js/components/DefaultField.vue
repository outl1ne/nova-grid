<template>
  <div v-if="field.visible" :class="fieldWrapperClasses">
    <div v-if="field.withLabel" :class="labelClasses">
      <slot>
        <FormLabel
          :label-for="labelFor || field.uniqueKey"
          class="space-x-1"
          :class="{ 'mb-2': shouldShowHelpText, flex: true }"
        >
          <span>
            {{ fieldLabel }}
          </span>
          <span v-if="field.required" class="text-red-500 text-sm">
            {{ __('*') }}
          </span>
        </FormLabel>
      </slot>
    </div>

    <div :class="controlWrapperClasses">
      <slot name="field" />

      <HelpText class="help-text-error" v-if="showErrors && hasError">
        {{ firstError }}
      </HelpText>

      <HelpText class="help-text" v-if="shouldShowHelpText" v-html="field.helpText" />
    </div>
  </div>
</template>

<script>
import { HandlesValidationErrors, mapProps } from '@/mixins';

export default {
  mixins: [HandlesValidationErrors],

  props: {
    field: { type: Object, required: true },
    fieldName: { type: String },
    showErrors: { type: Boolean, default: true },
    fullWidthContent: { type: Boolean, default: false },
    labelFor: { default: null },
    ...mapProps(['showHelpText']),
  },

  mounted() {
    this.applyParentElClasses();
  },

  watch: {
    'field.visible'(newValue, oldValue) {
      this.$nextTick(() => {
        if (newValue === true && oldValue === false) this.applyParentElClasses();
      });
    },
  },

  methods: {
    applyParentElClasses() {
      const parentElement = this.getParentElement(this);

      if (parentElement && parentElement.classList) {
        parentElement.classList.add(...this.fieldSizeClasses);
      }
    },

    getParentElement: function (el) {
      let currentEl = el.$el;
      while (currentEl) {
        const parentNode = currentEl.parentNode;

        // Reached end of document
        if (!parentNode) return null;

        if (currentEl.parentNode.classList && currentEl.parentNode.classList.contains('nova-grid--card')) {
          return currentEl;
        }

        currentEl = currentEl.parentNode;
      }
    },
  },

  computed: {
    fieldWrapperClasses() {
      // prettier-ignore
      return [
        'md:flex-row space-y-2 md:space-y-0',
        this.field.withLabel && !this.field.inline && !this.field.compact && 'py-4',
        this.field.withLabel && !this.field.inline && this.field.compact && 'py-4',
        this.field.stacked && 'md:flex-col md:space-y-2',
        this.hasSize && 'nova-grid--field-wrapper',
        'flex w-full',
      ]
    },

    labelClasses() {
      // prettier-ignore
      return [
        '!px-3 md:!px-6',
        this.hasSize ? 'w-full' : 'w-1/5',
        this.field.stacked && '',
        this.field.stacked && !this.field.inline && 'px-6 md:px-8',
        !this.field.stacked && !this.field.inline && 'px-6 md:px-8',
        this.hasSize && 'compact-nova-field-label',
      ]
    },

    controlWrapperClasses() {
      // prettier-ignore
      return [
        'w-full space-y-2 !px-3 md:!px-4',
        this.field.stacked && !this.field.inline && '',
        !this.field.stacked && !this.field.inline && 'px-6 md:px-8',
        !this.field.stacked && !this.field.inline && !this.field.fullWidth && !this.field.size && 'md:w-3/5',
        this.field.stacked && !this.field.inline && !this.field.fullWidth && !this.field.size && 'md:w-3/5',
        !this.field.stacked && !this.field.inline && this.field.fullWidth && !this.field.size && 'md:w-4/5',
        this.field.size && 'w-full py-2 px-1',
      ]
    },

    /**
     * Return the label that should be used for the field.
     */
    fieldLabel() {
      // If the field name is purposefully an empty string, then let's show it as such
      if (this.fieldName === '') {
        return '';
      }

      return this.fieldName || this.field.name || this.field.singularLabel;
    },

    /**
     * Determine help text should be shown.
     */
    shouldShowHelpText() {
      return this.showHelpText && this.field.helpText?.length > 0;
    },

    hasSize() {
      return this.field.size !== undefined;
    },

    fieldSizeClasses() {
      if (!this.field.size) return ['w-full'];
      return [this.field.size, 'border-r', 'border-gray-100', 'dark:border-gray-700', 'self-end'];
    },
  },
};
</script>

<style lang="scss">
[data-testid='content'] {
  form h1 + div {
    display: flex;
    flex-wrap: wrap;
  }
}

.nova-grid--field-wrapper {
  display: flex;
  flex-wrap: wrap;
}

.field-wrapper:last-child {
  border-style: solid;
}
</style>

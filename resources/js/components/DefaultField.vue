<template>
  <div :class="field.size || 'w-full'">
    <FieldWrapper :stacked="field.stacked" v-if="field.visible" :style="{ 'border-style': 'solid' }">
      <div class="px-6 md:px-8 mt-2 md:mt-0" :class="field.stacked ? 'md:pt-2 w-full' : 'w-full md:w-1/5 md:py-5'">
        <slot>
          <FormLabel :label-for="labelFor || field.uniqueKey" :class="{ 'mb-2': shouldShowHelpText, flex: true }">
            {{ fieldLabel }}
            <span v-if="field.required" class="text-red-500 text-sm">
              {{ __('*') }}
            </span>
          </FormLabel>
        </slot>
      </div>

      <div
        class="mt-1 md:mt-0 pb-5 px-6 md:px-8"
        :class="{
          'md:w-4/5': fullWidthContent,
          'w-full md:w-3/5 md:py-5': !field.stacked,
          'md:pt-2 w-full': field.stacked && fullWidthContent,
          'w-full': !!field.size,
        }"
      >
        <slot name="field" />

        <HelpText class="mt-2 help-text-error" v-if="showErrors && hasError">
          {{ firstError }}
        </HelpText>

        <HelpText class="help-text mt-2" v-if="shouldShowHelpText" v-html="field.helpText" />
      </div>
    </FieldWrapper>
  </div>
</template>

<script>
import { HandlesValidationErrors, mapProps } from 'laravel-nova';

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
    // If field has a size, this allows to use flex on card
    if (this.field.size) {
      this.$parent.$parent.$el.classList.add('flex-dom');
      this.$parent.$parent.$el.classList.add('flex-wrap');
      this.$parent.$parent.$el.classList.add('flex');

      if (document.getElementsByClassName('action').length > 0) {
        document.getElementsByClassName('action').item(0).parentElement.classList.add('flex');
        document.getElementsByClassName('action').item(0).parentElement.classList.add('flex-wrap');
      }
    }

    if (this.field.removeBottomBorder) {
      this.$el.children[0].classList.add('remove-bottom-border');
    } else {
      this.$el.children[0].classList.remove('remove-bottom-border');
    }
  },

  computed: {
    fieldClasses() {
      return this.fullWidthContent ? (this.field.stacked ? 'w-full' : 'w-4/5') : this.hasSize ? 'w-full' : 'w-1/2';
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
  },
};
</script>

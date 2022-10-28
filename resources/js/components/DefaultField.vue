<template>
  <FieldWrapper :stacked="field.stacked" v-if="field.visible" :class="fieldWrapperClasses">
    <div :class="fieldLabelClasses">
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
      class="mt-1 md:mt-0"
      :class="{
        'md:w-4/5': fullWidthContent,
        'px-8 mt-2': field.stacked,
        'w-full': !field.stacked,
        'w-full py-2 px-1': field.size,
        'w-full md:w-3/5 md:py-5': !field.size && !field.stacked,
      }"
    >
      <slot name="field" />

      <HelpText class="mt-2 help-text-error" v-if="showErrors && hasError">
        {{ firstError }}
      </HelpText>

      <HelpText class="help-text mt-2" v-if="shouldShowHelpText" v-html="field.helpText" />
    </div>
  </FieldWrapper>
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

    hasSize() {
      return this.field.size !== undefined;
    },

    fieldWrapperClasses() {
      if (this.hasSize) return 'nova-grid--field-wrapper';
      if (this.field.stacked) return 'flex w-full flex-col';
      return 'flex w-full';
    },

    fieldLabelClasses() {
      if (this.hasSize) return 'compact-nova-field-label';
      if (this.field.stacked) return 'w-1/5 px-8 pt-2';
      return 'w-1/5 py-6 px-8';
    },

    fieldValueClasses() {
      if (this.hasSize) return 'w-full';
      return this.fullWidthContent ? (this.field.stacked ? 'w-full py-6 px-8' : 'w-4/5 py-6 px-8') : 'w-1/2 py-6 px-8';
    },

    fieldSizeClasses() {
      if (!this.field.size) return ['w-full'];
      return [this.field.size, 'border-r', 'border-gray-100', 'dark:border-gray-700', 'self-end'];
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
  padding: 0.5rem 1rem;
}

.field-wrapper:last-child {
  border-style: solid;
}
</style>

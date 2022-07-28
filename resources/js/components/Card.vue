<template>
  <Card class="nova-grid--card">
    <slot />
  </Card>
</template>

<script>
import Card from 'nova/components/Card';

export default {
  components: { Card },
};
</script>

<style lang="scss">
form .nova-grid--card {
  display: flex;
  flex-wrap: wrap;

  > *:last-child .field-wrapper {
    border-style: none;
  }

  .translatable-field {
    position: relative;

    > .nova-translatable-locale-tabs {
      position: absolute;
      top: 6px;
      right: 0px;
    }
  }
}
</style>

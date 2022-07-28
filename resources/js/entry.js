import Card from './components/Card';
import DefaultField from './components/DefaultField';

Nova.booting((Vue, router, store) => {
  Vue.component('Card', Card);
  Vue.component('DefaultField', DefaultField);
});

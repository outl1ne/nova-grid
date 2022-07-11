import PanelItem from './components/PanelItem';
import DefaultField from './components/DefaultField';

Nova.booting((Vue, router, store) => {
  Vue.component('DefaultField', DefaultField);
  Vue.component('PanelItem', PanelItem);
});

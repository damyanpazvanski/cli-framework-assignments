import { createApp, ref, reactive, onMounted } from 'vue';

export default {
  props: {
    logs: {
      type: Array,
      required: true, 
    },
  },
  setup(props) {
    const getLogClass = (type) => {
      if (type === 'error') return 'log-error';
      if (type === 'success') return 'log-success';
      return 'log-info';
    };
  
    return { getLogClass }
  },
  template: `
    <section class="machine-display">
        <div style="font-size: 11px; text-transform: uppercase; color: #aaa; margin-bottom: 8px;">Vending Machine Output:</div>
        <div v-for="(log, idx) in logs" :key="idx" :class="getLogClass(log.type)" class="log-item">
        {{ log.text }}
        </div>
    </section>
  `
}

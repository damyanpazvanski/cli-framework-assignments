import { createApp } from 'vue'
import { ErrorTooltip } from '/assets/js/components/ErrorTooltip.js';
import DisplayComponent from '/assets/js/components/Display.js'
import VendingMachineComponent from '/assets/js/components/VendingMachine.js'

const app = createApp({
    components: {
        ErrorTooltip
    },
})

app.component('display-component', DisplayComponent)
app.component('vending-machine-component', VendingMachineComponent)

app.mount('#app-container')

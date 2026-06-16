import { createApp, ref, reactive, onMounted } from 'vue';

export const errorState = reactive({
    showErr: false,
    errMessage: '',
    trigger(msg) {
        this.errMessage = msg || 'An unexpected error occurred.';
        this.showErr = true;
        setTimeout(() => { this.showErr = false; }, 5000);
    },
});

export const ErrorTooltip = {
    name: 'ErrorTooltip',
    setup() {
        return { errorState }
    },
    template: `
        <div class="mdc-snackbar" :class="{'mdc-snackbar--open': errorState.showErr}" v-if="errorState.showErr">
            <div class="mdc-snackbar__surface" role="status" aria-relevant="additions" style="background-color: #b00020;">
            <div class="mdc-snackbar__label" aria-atomic="false" style="color: white;">
                {{ errorState.errMessage }}
            </div>
            <div class="mdc-snackbar__actions" aria-atomic="true">
                <button type="button" class="mdc-button mdc-snackbar__action" @click="errorState.showErr = false" style="color: white;">
                <span class="mdc-button__ripple"></span>
                <span class="mdc-button__label">Dismiss</span>
                </button>
            </div>
            </div>
        </div>
    `
}

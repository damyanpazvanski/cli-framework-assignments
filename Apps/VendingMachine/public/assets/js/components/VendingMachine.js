import { createApp, ref, reactive, onMounted } from 'vue';
import { errorState } from '/assets/js/components/ErrorTooltip.js';
import { handleApiRequest } from '/assets/js/components/APIRequest.js';

export default {
  setup() {
    const drinks = ref([]);
    const coins = ref([]);

    const newDrink = ref({ name: '', price: null });
    const newCoin = ref({ price: null });

    const addedMoneyAmount = ref({ price: 0 });
    const addedCoin = ref({ price: null });

    /**
     * { text: string, type: info, success, error }
     */
    const displayLogs = ref([]);

    const addNewLog = (text, type = 'info', limit = 3) => {
      // Add new item to the very beginning of the array
      // displayLogs.value.unshift({ text, type });
      displayLogs.value.push({ text, type });
      // Cap the array length
      displayLogs.value = displayLogs.value.slice(-limit);
    };

    const resetVM = () => {
      addedMoneyAmount.value = { price: 0 };
      addedCoin.value = { price: null };
      displayLogs.value = [];
    };

    const fetchVendingData = async () => {
      await handleApiRequest({
        endpoint: '/api/vending-machine',
        method: 'GET',
        onSuccess: (data) => {
          drinks.value = data.products;
          coins.value = data.coins;
        },
        onError: (err) => {
          errorState.trigger(err);
        },
      });
    };

    const putCoin = async () => {
      await handleApiRequest({
        endpoint: '/api/vending-machine/put-coin',
        method: 'POST',
        payload: {
          'coin': addedCoin.value.price,
          'fullAmount': addedMoneyAmount.value.price,
        },
        onSuccess: (data) => {
          if (!data.success) { addNewLog(data.msg, 'error'); return; }

          addedMoneyAmount.value = { price: addedMoneyAmount.value.price + addedCoin.value.price };
          addedCoin.value = { name: '', price: null };   // Reset

          addNewLog(data.msg, 'success');
        },
        onError: (err) => {
          errorState.trigger(err);
        },
      });
    };

    const getChange = async () => {
      await handleApiRequest({
        endpoint: '/api/vending-machine/get-change',
        method: 'POST',
        payload: {
          'fullAmount': addedMoneyAmount.value.price
        },
        onSuccess: (data) => {
          if (!data.success) { addNewLog(data.msg, 'error'); return; }

          addedMoneyAmount.value = { price: 0 };

          addNewLog(data.msg, 'success');
        },
        onError: (err) => {
          errorState.trigger(err);
        },
      });
    };

    const viewFullAmount = async () => {
      await handleApiRequest({
        endpoint: '/api/vending-machine/view-amount',
        method: 'POST',
        payload: {
          'fullAmount': addedMoneyAmount.value.price
        },
        onSuccess: (data) => {
          if (!data.success) { addNewLog(data.msg, 'error'); return; }

          addNewLog(data.msg, 'success');
        },
        onError: (err) => {
          errorState.trigger(err);
        },
      });
    };

    const buyDrink = async (id) => {
      const drink = drinks.value.find((d) => d.id == id);

      await handleApiRequest({
        endpoint: '/api/vending-machine/buy-product',
        method: 'POST',
        payload: {
          'product': drink.name,
          'fullAmount': addedMoneyAmount.value.price,
        },
        onSuccess: (data) => {
          if (!data.success) { addNewLog(data.msg, 'error'); return; }

          addedMoneyAmount.value = { price: addedMoneyAmount.value.price - drink.price };

          addNewLog(data.msg, 'success');
        },
        onError: (err) => {
          errorState.trigger(err);
        },
      });
    };
    
    const addDrink = async () => {
      await handleApiRequest({
        endpoint: '/api/vending-machine/products',
        method: 'POST',
        payload: {
          'name': newDrink.value.name,
          'price': newDrink.value.price,
        },
        onSuccess: (data) => {
          if (!data.success) { addNewLog(data.msg, 'error'); return; }

          drinks.value.push({ id: data.data.id, name: data.data.name, price: data.data.price, priceLbl: data.data.priceLbl });
          newDrink.value = { name: '', price: null };   // Reset

          addNewLog(data.msg, 'success');
        },
        onError: (err) => {
          errorState.trigger(err);
        },
      });
    };

    const deleteDrink = async (id) => {
      await handleApiRequest({
        endpoint: '/api/vending-machine/products/delete',
        method: 'POST',
        payload: {
          'productId': id,
        },
        onSuccess: (data) => {
          if (!data.success) { addNewLog(data.msg, 'error'); return; }

          drinks.value = drinks.value.filter(d => d.id !== id);

          addNewLog(data.msg, 'success');
        },
        onError: (err) => {
          errorState.trigger(err);
        },
      });
    };

    const addCoin = async () => {
      await handleApiRequest({
        endpoint: '/api/vending-machine/coins',
        method: 'POST',
        payload: {
          'price': newCoin.value.price,
        },
        onSuccess: (data) => {
          if (!data.success) { addNewLog(data.msg, 'error'); return; }

          coins.value.push({ id: data.data.id, price: data.data.price, priceLbl: data.data.priceLbl });
          newCoin.value = { price: null };    // Reset

          addNewLog(data.msg, 'success');
        },
        onError: (err) => {
          errorState.trigger(err);
        },
      });
    };
    
    const deleteCoin = async (id) => {
      await handleApiRequest({
        endpoint: '/api/vending-machine/coins/delete',
        method: 'POST',
        payload: {
          'coinId': id,
        },
        onSuccess: (data) => {
          if (!data.success) { addNewLog(data.msg, 'error'); return; }

          coins.value = coins.value.filter(c => c.id !== id);

          addNewLog(data.msg, 'success');
        },
        onError: (err) => {
          errorState.trigger(err);
        },
      });
    };

    onMounted(() => { fetchVendingData(); });

    return {
      displayLogs, drinks, coins, newDrink, newCoin, addedCoin,
      putCoin, getChange, viewFullAmount, buyDrink, resetVM, addDrink, deleteDrink, addCoin, deleteCoin
    };
  },
  template: `
      <!-- SECTION: Display Screen Messages -->
      <display-component :logs="displayLogs"></display-component>

      <!-- SECTION: Vending Machine -->
      <div class="dashboard-col">
        <div class="mdc-card mdc-elevation--z1">
          <div class="form-inline-row">
            <small>Work with the Vending Machine</small>
            <div style="width: 100px">
              <button class="mdc-button" style="flex: 1;" @click="resetVM">
                  <span class="mdc-button__ripple"></span>
                  <span class="mdc-button__label">Reset</span>
              </button>
            </div>
          </div>

          <div class="form-inline-row">
            <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label" style="flex: 2;">
                <span class="mdc-notched-outline">
                <span class="mdc-notched-outline__leading"></span>
                <span class="mdc-notched-outline__trailing"></span>
                </span>
                <input type="number" step="0.01" class="mdc-text-field__input" placeholder="Value (e.g. 0.50)" v-model.number="addedCoin.price">
            </label>

            <button class="mdc-button mdc-button--raised" style="flex: 1;" @click="putCoin">
                <span class="mdc-button__ripple"></span>
                <span class="mdc-button__label">Put a Coin</span>
            </button>
            <button class="mdc-button mdc-button--raised" style="flex: 1;" @click="viewFullAmount">
                <span class="mdc-button__ripple"></span>
                <span class="mdc-button__label">View Amount</span>
            </button>
            <button class="mdc-button mdc-button--raised" style="flex: 1;" @click="getChange">
                <span class="mdc-button__ripple"></span>
                <span class="mdc-button__label">Get Change</span>
            </button>
          </div>
        </div>
      </div>

      <!-- SECTION: Drinks & Coins Settings -->
      <div class="dashboard-grid">
          <!-- Left Column: Drinks Section -->
          <div class="dashboard-col">
          <div class="mdc-card mdc-elevation--z1">
              <h2 class="mdc-typography--headline5 card-title">Drinks Inventory</h2>
              
              <!-- Created Items Area -->
              <div class="items-list">
              <div v-for="drink in drinks" :key="drink.id" class="custom-chip">              
                  <button class="mdc-button" @click="buyDrink(drink.id)">buy</button>
                  <span>{{ drink.name }}: {{ drink.priceLbl }}</span>
                  <i class="material-icons delete-icon" @click="deleteDrink(drink.id)">x</i>
              </div>
              </div>
              
              <hr style="border: 0; border-top: 1px solid #e0e0e0;">
              <h3 class="mdc-typography--subtitle2" style="color: #666; margin-top: 16px;">Add New Drink</h3>
              
              <!-- Add Drink Inputs -->
              <div class="form-inline-row">
              <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
                  <span class="mdc-notched-outline">
                  <span class="mdc-notched-outline__leading"></span>
                  <span class="mdc-notched-outline__trailing"></span>
                  </span>
                  <input type="text" class="mdc-text-field__input" placeholder="Name" v-model="newDrink.name">
              </label>

              <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label">
                  <span class="mdc-notched-outline">
                  <span class="mdc-notched-outline__leading"></span>
                  <span class="mdc-notched-outline__trailing"></span>
                  </span>
                  <input type="number" step="0.01" class="mdc-text-field__input" placeholder="Price" v-model.number="newDrink.price">
              </label>

              <button class="mdc-button mdc-button--raised" @click="addDrink">
                  <span class="mdc-button__ripple"></span>
                  <span class="mdc-button__label">Add</span>
              </button>
              </div>
          </div>
          </div>

          <!-- Right Column: Coins Section -->
          <div class="dashboard-col">
            <div class="mdc-card mdc-elevation--z1">
                <h2 class="mdc-typography--headline5 card-title">Accepted Coins</h2>
                
                <!-- Created Items Area -->
                <div class="items-list">
                <div v-for="coin in coins" :key="coin.id" class="custom-chip">
                    <span>+ {{ coin.priceLbl }}</span>
                    <i class="material-icons delete-icon" @click="deleteCoin(coin.id)">x</i>
                </div>
                </div>

                <hr style="border: 0; border-top: 1px solid #e0e0e0;">
                <h3 class="mdc-typography--subtitle2" style="color: #666; margin-top: 16px;">Add New Coin</h3>

                <!-- Add Coin Inputs -->
                <div class="form-inline-row">
                  <label class="mdc-text-field mdc-text-field--outlined mdc-text-field--no-label" style="flex: 2;">
                      <span class="mdc-notched-outline">
                      <span class="mdc-notched-outline__leading"></span>
                      <span class="mdc-notched-outline__trailing"></span>
                      </span>
                      <input type="number" step="0.01" class="mdc-text-field__input" placeholder="Value (e.g. 0.50)" v-model.number="newCoin.price">
                  </label>

                  <button class="mdc-button mdc-button--raised" style="flex: 1;" @click="addCoin">
                      <span class="mdc-button__ripple"></span>
                      <span class="mdc-button__label">Add</span>
                  </button>
                </div>
            </div>
          </div>
      </div>
  `
}

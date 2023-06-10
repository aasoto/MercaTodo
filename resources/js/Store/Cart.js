import { defineStore } from "pinia";
import { computed, ref } from "vue";

export const useCartStore = defineStore('cart', () => {

    /************** STATE ****************/
    const cart = ref([]);
    const current_user_id = ref();

    /************** GETTERS ****************/
    const order = computed(() => {
        return cart.value.map( item => ({
                ...item,
                totalPrice: item.price * item.quantity
            })
        );
    });

    const numberOfProducts = computed(() => {
        return cart.value.length;
    });

    const numberQuantityOfProducts = computed((quantity = 0) => {
        cart.value.forEach( item => {
            quantity = quantity + item.quantity;
        });
        return quantity;
    });

    const totalPriceOrder = computed((price = 0) => {
        order.value.forEach( item => {
            price = price + item.totalPrice;
        });
        return price;
    });

    /************** ACTIONS ****************/
    const currentUser = (id) => {
        current_user_id.value = id;
    }

    const loadCart = () => {
        if (localStorage.getItem("cartBackup"+current_user_id.value)) {
            cart.value = JSON.parse(localStorage.getItem("cartBackup"+current_user_id.value));
        }
    }

    const add = (id, name, slug, price, quantity) => {
        cart.value.push({ id, name, slug, price, quantity });
        localStorage.setItem("cartBackup"+current_user_id.value, JSON.stringify(cart.value));
    }

    const restore = (cartData) => {
        cart.value = [];
        cartData.forEach( item => {
            const {id, name, slug, price, quantity} = item;
            cart.value.push({ id, name, slug, price, quantity });
        });
        localStorage.setItem("cartBackup"+current_user_id.value, JSON.stringify(cart.value));
    }

    const find = (id) => {
        return cart.value.find((item) => item.id === id);
    }

    const update = (id, quantity) => {
        cart.value.forEach( item => {
            if (item.id === id) {
                item.quantity = quantity;
            }
        });
        localStorage.setItem("cartBackup"+current_user_id.value, JSON.stringify(cart.value));
    }

    const remove = (id) => {
        const index = cart.value.indexOf(find(id));
        cart.value.splice(index, 1);
        localStorage.setItem("cartBackup"+current_user_id.value, JSON.stringify(cart.value));
    }

    const emptyCart = () => {
        cart.value = [];
        localStorage.setItem("cartBackup"+current_user_id.value, JSON.stringify(cart.value));
    }

    const logoutCart = () => {
        cart.value = [];
    }

    return {
        cart,
        order,
        numberOfProducts,
        numberQuantityOfProducts,
        totalPriceOrder,
        currentUser,
        loadCart,
        add,
        restore,
        find,
        update,
        remove,
        emptyCart,
        logoutCart,
    };
});

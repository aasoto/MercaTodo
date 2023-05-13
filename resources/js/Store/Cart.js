import { defineStore } from "pinia";
import { computed, ref } from "vue";

export const useCartStore = defineStore('cart', () => {
    const cart = ref([]);

    const order = computed(() => {
        return cart.value.forEach( item => {
            return {
                name: item.name,
                price: item.price,
                quantity: item.quantity,
                totalPrice: item.price * item.quantity
            };
        });
    });

    const add = (id, name, price, quantity) => {
        cart.value.push({ id, name, price, quantity });
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
    }

    const remove = (id) => {
        const index = cart.value.indexOf(find(id));
        cart.value.splice(index, 1);
    }

    return {
        cart,
        order,
        add,
        find,
        update,
        remove,
    };
});

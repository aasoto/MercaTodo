import { defineStore } from "pinia";
import { computed, ref } from "vue";

export const useCartStore = defineStore('cart', () => {
    const cart = ref([]);

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
    })

    const add = (id, name, slug, price, quantity) => {
        cart.value.push({ id, name, slug, price, quantity });
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
        numberOfProducts,
        numberQuantityOfProducts,
        totalPriceOrder,
        add,
        find,
        update,
        remove,
    };
});

import { defineStore } from "pinia";
import { ref } from "vue";

export const useSignedRoleStore = defineStore('signedRole', () => {

    const role = ref('');

    const assignRole = (name) => {
        role.value = name;
    }

    return {
        role,
        assignRole,
    }
});

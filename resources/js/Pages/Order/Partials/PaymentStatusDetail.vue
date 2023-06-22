<script setup>
import MulticolorInfo from '@/Components/Infos/MulticolorInfo.vue';

const props = defineProps({
    paymentStatus: String,
    url: String,
});
</script>
<template>
    <MulticolorInfo color="red" v-if="paymentStatus == 'canceled'">
        Estado: CANCELADO
    </MulticolorInfo>
    <MulticolorInfo color="green" v-if="paymentStatus == 'paid'">
        Estado: PAGADO
    </MulticolorInfo>
    <MulticolorInfo color="yellow" v-if="(paymentStatus == 'pending') && url">
        Estado: PENDIENTE
    </MulticolorInfo>
    <MulticolorInfo color="blue" v-else-if="paymentStatus == 'pending'">
        Estado: SIN LINK DE PAGO
    </MulticolorInfo>
    <MulticolorInfo color="purple" v-if="paymentStatus == 'waiting'">
        Estado: PAGO POR CONFIRMAR
    </MulticolorInfo>
    <MulticolorInfo color="orange" v-if="paymentStatus == 'verify_bank'">
        Estado: VERIFICAR TRANSACCIÓN CON SU ENTIDAD BANCARIA
    </MulticolorInfo>
    <MulticolorInfo color="amber" v-if="paymentStatus == 'approved_partial'">
        Estado: PAGO INCOMPLETO
    </MulticolorInfo>
    <MulticolorInfo color="zinc" v-if="paymentStatus == 'partial_expired'">
        Estado: PAGO EXPIRADO
    </MulticolorInfo>
</template>

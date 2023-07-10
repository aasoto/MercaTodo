<script setup>
import { ref, watch } from 'vue';
import { Head, router } from '@inertiajs/vue3';
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TitlePage from '@/Components/TitlePage.vue';
import BasicBodyPage from '@/Components/Body/BasicBodyPage.vue';
import DropdownButtonStrict from '@/Components/Buttons/DropdownButtonStrict.vue';
import DropdownItem from '@/Components/Buttons/DropdownItem.vue';
import BasicTable from '@/Components/Tables/BasicTable.vue';
import TableCol from '@/Components/Tables/Basic/TableCol.vue';
import TableRow from '@/Components/Tables/Basic/TableRow.vue';
import NotFoundMessage from '@/Components/NotFoundMessage.vue';
import Pagination from '@/Components/Pagination.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import InputLabel from '@/Components/InputLabel.vue';

const props = defineProps({
    filters: Object,
    products: Object,
    productsCategories: Object,
    units: Object,
    success: String,
});

const search = ref(props.filters.search);
const category = ref(props.filters.category);
const minStock = ref(props.filters.minStock);
const maxStock = ref(props.filters.maxStock);
const minPrice = ref(props.filters.minPrice);
const maxPrice = ref(props.filters.maxPrice);
const unitCode = ref(props.filters.unitCode);
const availability = ref(props.filters.availability);
const enabled = ref('');

watch(search, value => {
    getResults();
});

watch(category, value => {
    getResults();
});

watch(minStock, value => {
    getResults();
});

watch(maxStock, value => {
    getResults();
});

watch(minPrice, value => {
    getResults();
});

watch(maxPrice, value => {
    getResults();
});

watch(unitCode, value => {
    getResults();
});

watch(availability, value => {
    getResults();
});

const btnCategoryText = ref('Categoría');
const setCategory = (productCategory) => {
    if (productCategory) {
        category.value = productCategory;
        btnCategoryText.value = productCategory;
    } else {
        category.value = '';
        btnCategoryText.value = 'Categoría';
    }
}

const btnUnitText = ref('Unidades');
const setUnit = (code, name) => {
    if (code && name) {
        unitCode.value = code;
        btnUnitText.value = name;
    } else {
        unitCode.value = '';
        btnUnitText.value = 'Unidades';
    }
}

const btnAvailabilityText = ref('Disponilidad');
const setAvailability = (value) => {
    switch (value) {
        case 'enabled':
            availability.value = true;
            enabled.value = 'true';
            btnAvailabilityText.value = 'Habilitados';
            break;
        case 'disabled':
            availability.value = false;
            enabled.value = 'false';
            btnAvailabilityText.value = 'Inhabilitados';
            break;
        default:
            availability.value = '';
            enabled.value = '';
            btnAvailabilityText.value = 'Disponilidad';
            break;
    }
}

const getResults = () => {
    router.get(route('product.report.create'), {
        search: search.value,
        category: category.value,
        minStock: minStock.value,
        maxStock: maxStock.value,
        minPrice: minPrice.value,
        maxPrice: maxPrice.value,
        unitCode: unitCode.value,
        availability: availability.value,
    }, {
        preserveState: true,
        replace: true,
    });
}

const generateReport = () => {
    router.post(route('product.report.export'), {
        search: search.value,
        category: category.value,
        min_stock: minStock.value,
        max_stock: maxStock.value,
        min_price: minPrice.value,
        max_price: maxPrice.value,
        unit_code: unitCode.value,
        availability: enabled.value,
    });
}

const tableTitles = ['Articulo', 'Categoría', 'Precio', 'Unidad', 'Stock', 'Habilitado'];

</script>
<template>
    <Head title="Reporte de productos" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <TitlePage>
                    Reporte de productos
                </TitlePage>
            </div>
        </template>
        <BasicBodyPage>
            <div class="flex flex-col justify-center items-center">
                <div class="w-full mt-5 grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="col-span-1">
                        <input
                            v-model="search"
                            type="text"
                            class="w-full px-5 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            placeholder="Buscar..."
                        >
                    </div>
                    <DropdownButtonStrict :btn-label="btnCategoryText" class="col-span-1">
                        <DropdownItem @click="setCategory()">
                            Todas
                        </DropdownItem>
                        <DropdownItem
                            v-for="productCategory in productsCategories"
                            @click="setCategory(productCategory.name)"
                            :key="productCategory.id"
                        >
                            {{ productCategory.name }}
                        </DropdownItem>
                    </DropdownButtonStrict>
                    <DropdownButtonStrict :btn-label="btnUnitText" class="col-span-1">
                        <DropdownItem @click="setUnit()">
                            Todas
                        </DropdownItem>
                        <DropdownItem
                            v-for="unit in units"
                            @click="setUnit(unit.code, unit.name)"
                            :key="unit.id"
                        >
                            {{ unit.name }}
                        </DropdownItem>
                    </DropdownButtonStrict>
                    <DropdownButtonStrict :btn-label="btnAvailabilityText" class="col-span-1">
                        <DropdownItem @click="setAvailability()">
                            Todas
                        </DropdownItem>
                        <DropdownItem @click="setAvailability('enabled')">
                            Habilitado
                        </DropdownItem>
                        <DropdownItem @click="setAvailability('disabled')">
                            Inhabilitado
                        </DropdownItem>
                    </DropdownButtonStrict>
                    <div class="rounded-md border border-gray-300 p-1 col-span-1">
                        <InputLabel class="translate-x-7 -translate-y-3 bg-white dark:bg-gray-800 w-48 px-3">
                                Entre stocks de productos
                        </InputLabel>
                        <div class="flex justify-center items-center gap-2">
                            <input
                                v-model="minStock"
                                type="number"
                                placeholder="Stock min."
                                class="px-2 py-[10px] w-32 bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            >
                            <input
                                v-model="maxStock"
                                type="number"
                                placeholder="Stock max."
                                class="px-2 py-[10px] w-32 bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            >
                        </div>
                    </div>
                    <div class="rounded-md border border-gray-300 p-1 col-span-1">
                        <InputLabel class="translate-x-5 -translate-y-3 bg-white dark:bg-gray-800 w-52 px-3">
                                Entre precios de productos
                        </InputLabel>
                        <div class="flex justify-center items-center gap-2">
                            <input
                                v-model="minPrice"
                                type="number"
                                placeholder="Precio min."
                                class="px-2 py-[10px] w-32 bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            >
                            <input
                                v-model="maxPrice"
                                type="number"
                                placeholder="Precio max."
                                class="px-2 py-[10px] w-32 bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            >
                        </div>
                    </div>
                    <SuccessButton @click="generateReport()" class="col-span-1">
                        Generar reporte de products
                    </SuccessButton>
                </div>
                <BasicTable v-if="products.data.length > 0" :titles="tableTitles">
                    <TableRow v-for="product in products.data">
                        <TableCol class="capitalize">
                            {{ product.name }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ product.category }}
                        </TableCol>
                        <TableCol class="text-right">
                            {{ product.price.toLocaleString('es-CO', { style: 'currency', currency: 'COP'}) }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ product.unit }}
                        </TableCol>
                        <TableCol class="text-center">
                            {{ product.stock }}
                        </TableCol>
                        <TableCol class="capitalize">
                            {{ product.availability ? 'Habilitado' : 'Inhabilitado' }}
                        </TableCol>
                    </TableRow>
                </BasicTable>
                <NotFoundMessage v-else class="m-5"/>
                <Pagination class="my-6" :links="products.links" />
            </div>
        </BasicBodyPage>
        <AlertSuccess
            v-if="success === 'Products report generated.'"
            title="¡Bien Hecho!"
            text="La exportación del reporte de productos está siendo procesado, una vez este proceso termine usted recibirá un correo electronico con el link de descarga del archivo."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>

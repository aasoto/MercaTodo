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

const props = defineProps({
    filters: Object,
    products: Object,
    productsCategories: Object,
    success: String,
});

const category = ref(props.filters.category);

watch(category, value => {
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

const getResults = () => {
    router.get(route('product.report.create'), {
        category: category.value,
    }, {
        preserveState: true,
        replace: true,
    });
}

const generateReport = () => {
    router.post(route('product.report.export'), {
        category: category.value,
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

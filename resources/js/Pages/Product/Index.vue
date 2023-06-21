<script setup>
import { ref, watch } from 'vue';

import { Head, Link, router, useForm } from '@inertiajs/vue3';

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import { EyeIcon, TrashIcon } from '@heroicons/vue/24/outline';
import { CheckIcon, PencilSquareIcon, XMarkIcon } from '@heroicons/vue/24/solid';

import AlertQuestion from '@/Components/Alerts/AlertQuestion.vue';
import AlertSuccess from '@/Components/Alerts/AlertSuccess.vue';
import BasicTable from '@/Components/Tables/BasicTable.vue';
import BasicBodyPage from '@/Components/Body/BasicBodyPage.vue';
import BtnSmDanger from '@/Components/Buttons/BtnSmDanger.vue';
import BtnSmPrimary from '@/Components/Buttons/BtnSmPrimary.vue';
import BtnSmWarning from '@/Components/Buttons/BtnSmWarning.vue';
import DropdownButtonStrict from '@/Components/Buttons/DropdownButtonStrict.vue';
import DropdownItem from '@/Components/Buttons/DropdownItem.vue';
import DropdownLink from '@/Components/Buttons/DropdownLink.vue';
import LoadingModal from '@/Components/LoadingModal.vue';
import NotFoundMessage from '@/Components/NotFoundMessage.vue';
import Pagination from '@/Components/Pagination.vue';
import SuccessButton from '@/Components/Buttons/SuccessButton.vue';
import TableCol from '@/Components/Tables/Basic/TableCol.vue';
import TableRow from '@/Components/Tables/Basic/TableRow.vue';
import TitlePage from '@/Components/TitlePage.vue';

const props = defineProps({
    filters: Object,
    products: Object,
    products_categories: Object,
    success: String,
});

const alertDelete = ref(false);

const form = useForm({
    slug: '',
});

const resetAlertDelete = () => {
    alertDelete.value = false;
}

const showAlertDelete = (slug) => {
    alertDelete.value = true;
    form.slug = slug;
}

const showProduct = (slug) => {
    form.slug = slug;
    form.get(route('product.show', form.slug));
}

const deleteProduct = () => {
    form.delete(route('product.destroy', form.slug));
}

const search = ref(props.filters.search);
const category = ref(props.filters.category);
const availability = ref(props.filters.availability);

watch(search, value => {
    getResults();
});

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

watch(availability, value => {
    getResults();
});

const btnAvailabilityText = ref('Disponilidad');

const setAvailability = (value) => {
    switch (value) {
        case 'enabled':
            availability.value = true;
            btnAvailabilityText.value = 'Habilitados';
            break;
        case 'disabled':
            availability.value = false;
            btnAvailabilityText.value = 'Inhabilitados';
            break;
        default:
            availability.value = '';
            btnAvailabilityText.value = 'Disponilidad';
            break;
    }
}

const getResults = () => {
    router.get('/products', {
        search: search.value,
        category: category.value,
        availability: availability.value
    }, {
        preserveState: true,
        replace: true,
    });
}

const tableTitles = ['Articulo', 'Categoría', 'Precio', 'Unidad', 'Stock', 'Habilitado', 'Acciones'];
</script>

<template>
    <Head title="Listado de productos" />
    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <TitlePage>
                    Listado de productos
                </TitlePage>
                <DropdownButtonStrict btn-label="Gestión de dependencias">
                    <DropdownLink :href="route('product_category.index')">
                        Categorías
                    </DropdownLink>
                    <DropdownLink :href="route('unit.index')">
                        Unidades
                    </DropdownLink>
                </DropdownButtonStrict>
            </div>
        </template>

        <BasicBodyPage>
            <div class="flex flex-col justify-center items-center">
                <div class="w-full mt-5 grid grid-cols-12 gap-4">
                    <Link :href="route('product.create')" class="col-span-3">
                        <SuccessButton class="w-full">Agregar productos</SuccessButton>
                    </Link>
                    <div class="col-span-5">
                        <input
                            v-model="search"
                            type="text"
                            class="w-full px-5 py-[10px] bg-transparent text-black dark:text-white border border-gray-400 rounded-md placeholder:italic"
                            placeholder="Buscar..."
                        >
                    </div>
                    <DropdownButtonStrict :btn-label="btnCategoryText" class="col-span-2">
                        <DropdownItem @click="setCategory()">
                            Todas
                        </DropdownItem>
                        <DropdownItem
                            v-for="product_category in products_categories"
                            @click="setCategory(product_category.name)"
                            :key="product_category.id"
                        >
                            {{ product_category.name }}
                        </DropdownItem>
                    </DropdownButtonStrict>
                    <DropdownButtonStrict :btn-label="btnAvailabilityText" class="col-span-2">
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
                        <TableCol>
                            <CheckIcon v-if="product.availability" class="w-8 h-8 text-green-600 mx-auto"/>
                            <XMarkIcon v-else class="w-8 h-8 text-red-600 mx-auto"/>
                        </TableCol>
                        <TableCol class="flex justify-center items-center gap-2">
                            <BtnSmPrimary @click="showProduct(product.slug)">
                                <EyeIcon class="w-4 h-4"/>
                            </BtnSmPrimary>
                            <Link :href="route('product.edit', product.slug)">
                                <BtnSmWarning>
                                    <PencilSquareIcon class="w-4 h-4"/>
                                </BtnSmWarning>
                            </Link>
                            <BtnSmDanger @click="showAlertDelete(product.slug)">
                                <TrashIcon class="w-4 h-4"/>
                            </BtnSmDanger>
                        </TableCol>
                    </TableRow>
                </BasicTable>
                <NotFoundMessage v-else class="m-5"/>
                <Pagination class="my-6" :links="products.links" />
            </div>
        </BasicBodyPage>
        <AlertSuccess
            v-if="success === 'Product created.'"
            title="¡Bien Hecho!"
            text="Producto guardado satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
        <AlertQuestion
            v-if="alertDelete"
            title="¿Desea eliminar este producto?"
            text="Si así lo desea de click en el botón eliminar."
            :delete-register="deleteProduct"
            :btn-delete="true"
            :close="false"
            :reset-alert="resetAlertDelete"
            :btn-close="true"
        />
        <LoadingModal v-show="form.processing"/>
        <AlertSuccess
            v-if="success === 'Product deleted.'"
            icon="success"
            title="¡Listo!"
            text="Producto eliminado satisfactoriamente."
            :close="false"
            :btn-close="true"
        />
    </AuthenticatedLayout>
</template>

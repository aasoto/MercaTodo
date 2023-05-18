<?php

namespace App\Support\Services\Product;

use App\Domain\Product\Dtos\StoreProductData;
use App\Domain\Product\Dtos\UpdateProductData;
use App\Support\Traits\StorageFiles;

class ImagesServices
{
    use StorageFiles;

    /**
     * @param StoreProductData|UpdateProductData $data
     * @return array<mixed> $data_filtered
     */
    public function Save(StoreProductData|UpdateProductData $data): array
    {
        $counter = 0;
        $data_filtered = [
            'name' => $data->name,
            'slug' => $data->slug,
            'products_category_id' => $data->products_category_id,
            'barcode' => $data->barcode,
            'description' => $data->description,
            'price' => $data->price,
            'unit' => $data->unit,
            'stock' => $data->stock,
            'picture_1' => '',
            'picture_2' => '',
            'picture_3' => '',
            'availability' => $data->availability,
        ];

        if (isset($data->picture_1)) {
            $counter++;
            $data_filtered['picture_1'] = $this->upload($data->picture_1, 'images/products', $counter);
        } else {
            unset($data_filtered['picture_1']);
        }

        if (isset($data->picture_2)) {
            $counter++;
            $data_filtered['picture_2'] = $this->upload($data->picture_2, 'images/products', $counter);
        } else {
            unset($data_filtered['picture_2']);
        }

        if (isset($data->picture_3)) {
            $counter++;
            $data_filtered['picture_3'] = $this->upload($data->picture_3, 'images/products', $counter);
        } else {
            unset($data_filtered['picture_3']);
        }

        return $data_filtered;
    }

    /**
     * @param UpdateProductData $data
     * @return array<mixed> $data
     */
    public function Update(UpdateProductData $data, string $files): array
    {
        $data = $this->Save($data);

        $this->Delete(json_decode($files, true), true, $data);

        return $data;
    }

    /**
     * @param array<mixed> $files
     * @param array<mixed> $data
     */
    public function Delete(array $files, bool $updating = false, array $data = null): void
    {
        if (!$updating) {
            $data = $files;
        }

        if (isset($data['picture_1']) && isset($files['picture_1'])) {
            $this->remove('images/products', $files['picture_1']);
        }

        if (isset($data['picture_2']) && isset($files['picture_2'])) {
            $this->remove('images/products', $files['picture_2']);
        }

        if (isset($data['picture_3']) && isset($files['picture_3'])) {
            $this->remove('images/products', $files['picture_3']);
        }
    }
}

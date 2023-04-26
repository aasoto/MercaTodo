<?php

namespace App\Classes\Product;

use App\Traits\StorageFiles;

class Images
{
    use StorageFiles;

    public function Save(array $data): array
    {
        $counter = 0;

        if (isset($data['picture_1'])) {
            $counter++;
            $data['picture_1'] = $this->upload($data['picture_1'], 'images/products', $counter);
        } else {
            unset($data['picture_1']);
        }

        if (isset($data['picture_2'])) {
            $counter++;
            $data['picture_2'] = $this->upload($data['picture_2'], 'images/products', $counter);
        } else {
            unset($data['picture_2']);
        }

        if (isset($data['picture_3'])) {
            $counter++;
            $data['picture_3'] = $this->upload($data['picture_3'], 'images/products', $counter);
        } else {
            unset($data['picture_3']);
        }

        return $data;
    }

    public function Update(array $data, string $files): array
    {
        $data = $this->Save($data);

        $this->Delete(json_decode($files, true));

        return $data;
    }

    public function Delete(array $files): void
    {
        if (isset($files['picture_1'])) {
            $this->remove('images/products/', $files['picture_1']);
        }

        if (isset($files['picture_2'])) {
            $this->remove('images/products/', $files['picture_2']);
        }

        if (isset($files['picture_3'])) {
            $this->remove('images/products/', $files['picture_3']);
        }
    }
}

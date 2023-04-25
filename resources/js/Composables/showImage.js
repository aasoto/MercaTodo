
export function useShowImage(image, elementInput, elementShow) {

    let charged, error;

    if (image.type !== 'image/jpeg' && image.type !== 'image/png') {
        image = '';
        elementInput.value = '';
        charged = false;
        error = true;
    } else {
        const readerImage = new FileReader;
        readerImage.readAsDataURL(image);
        readerImage.addEventListener('load', event => {
            const routeImage = event.target.result;
            elementShow.src = routeImage;
            charged = true;
            error = false;
        });
    }

    return {
        image,
        elementInput,
        elementShow,
        charged,
        error,
    }
}

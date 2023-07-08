export function dateGMT (date, timezone) {
    if (date) {
        let res = new Date(date);
        res.setHours(res.getHours() + timezone);

        const year = res.getFullYear();
        const month = res.getMonth() + 1;
        const day = res.getDate();
        const hours = res.getHours();
        const minutes = res.getMinutes();
        const seconds = res.getSeconds();
        return year+'-'+month+'-'+day+' '+hours+':'+minutes+':'+seconds;
    } else {
        return '';
    }
}

// Case insensitive includes
export const includeInList = (list, value) => {
    if(list?.length < 1) return;
    let regex = new RegExp(list.join( "|" ), "i");
    return regex.test(value)
}
/**
 * This function converts a Date Object to YYYY-MM-DD format
 * 
 * @param {Date} date The Date Object to be converted
 * @param {String} glue (default: '-') The string that combines the date
 * @param {Boolean} isLZ (default: true) If leading zero is added to month & date
 * 
 * @returns {String} The formatted string 
 */
export function date_ymd(date, glue = '-', isLZ = true)
{
    const month = date.getMonth() + 1;
    const day = date.getDate();

    return [
        date.getFullYear(),
        isLZ ? lz(month) : month,
        isLZ ? lz(day) : day
    ].join(glue);
}




/**
 * This function converts a Date Object to HH:MM format
 * 
 * @param {Date} date The Date Object to be converted
 * @param {String} glue (default: ':') The string that combines the time
 * @param {Boolean} isLZ (default: true) If leading zero is added to hour & minute
 * 
 * @returns {String} The formatted string
 */
export function date_hm(date, glue = ':', isLZ = true)
{
    const hour = date.getHours();
    const minute = date.getMinutes();

    return [
        isLZ ? lz(hour) : hour,
        isLZ ? lz(minute) : minute
    ].join(glue);
}


/**
 * This function checks if the date string is in YYYY-MM-DD format
 * 
 * @param {String} date The date string to check
 * 
 * @returns {Boolean} True if string is in YYYY-MM-DD format
 */
export function is_string_ymd(date)
{
    return /^[0-9]{4}\-[0-9]{2}\-[0-9]{2}$/.test(date);
}


/**
 * The function checks if the time string is in HH:MM format
 * 
 * @param {String} time The time string to check
 * 
 * @returns {Boolean} True if string is in HH:MM format
 */
export function is_string_hm(time)
{
    return /^[0-9]{2}\:[0-9]{2}$/.test(time);
}




/**
 * This function adds a leading zero if it is a single digit number
 * 
 * @param {Integer} number The number that will be added a leading zero
 * 
 * @returns {String} The number with leading zero
 */
export function lz(number)
{
    return `${number < 10 ? 0 : ''}${number}`;
}




/**
 * The full name of all the months
 * 
 * @var {Array} MONTHS_LONG
 */
export const MONTHS_LONG = [
    'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'
];




/**
 * The short name of all the months. e.g. Jan, Feb, Mar...
 * 
 * @var {Array} MONTHS_SHORT
 */
export const MONTHS_SHORT = MONTHS_LONG.map(month => month.slice(0, 3));




/**
 * The full name of all the days in a week
 * 
 * @var {Array} DAYS_LONG
 */
export const DAYS_LONG = [
    'Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'
];




/**
 * The short name of all the days in a week. e.g. Sun, Mon, Tue
 * 
 * @var {Array} DAYS_SHORT
 */
export const DAYS_SHORT = DAYS_LONG.map(day => day.slice(0, 3));
export const user_tz = Intl.DateTimeFormat().resolvedOptions().timeZone
export const userDate = new Date().toLocaleString('en-US', {
    timeZone: user_tz,
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
});
export const userDateISO = new Date(userDate).toISOString().slice(0, 10); // YYYY-MM-DD
export const serverDate = new Date(userDate).toLocaleString('en-US', {
    timeZone: 'Europe/London',
    year: 'numeric',
    month: '2-digit',
    day: '2-digit',
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit'
});
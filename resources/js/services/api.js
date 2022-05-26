const baseUrl = '/api/v1/dashboard/'
// Auth
const LOGIN_URL = baseUrl + 'auth/login'
const UPDATE_PASSWORD_URL = baseUrl + 'auth/update_password'
const LOGOUT_URL = baseUrl + 'auth/logout'
// SuperAccess
const MASTER_RECORD_URL = baseUrl + 'master_records'
const DEPARTMENT_URL = baseUrl + 'departments'
const COMPANY_URL = baseUrl + 'companies'
const EMPLOYEE_URL = baseUrl + 'employees'

export default {
    // Auth
    LOGIN_URL,
    UPDATE_PASSWORD_URL,
    LOGOUT_URL,
    // SuperAccess
    MASTER_RECORD_URL,
    DEPARTMENT_URL,
    COMPANY_URL,
    EMPLOYEE_URL
}
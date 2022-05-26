//Dashboard Components
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import Dashboard from '@/pages/dashboard/index.vue'

//Auth Components
import AuthLayout from '@/layouts/AuthLayout.vue'
import Login from '@/pages/auth/login.vue'

//NotFound Components
import NotFound from '@/layouts/NotFound.vue'
// SuperAccess
import Department from '@/pages/departments/index.vue'
import Employee from '@/pages/employees/index.vue'
import Company from '@/pages/companies/index.vue'
import ChangePassword from '@/pages/settings/change_password.vue'

const routes = () => {
    return [
        {
            path: '/auth', component: AuthLayout,
            children: [
                {
                    path: '/login', name: 'login', component: Login,
                    meta: {
                        guest: true
                    },
                }
            ]
        },
        {
            path: '/', component: DashboardLayout,
            children: [
                {
                    path: '/', name: 'dashboard', component: Dashboard,
                    meta: { requiredAuth: true }
                },
                {
                    path: '/departments', name: 'departments', component: Department,
                    meta: { requiredAuth: true }
                },
                {
                    path: '/employees', name: 'employees', component: Employee,
                    meta: { requiredAuth: true }
                },
                {
                    path: '/companies', name: 'companies', component: Company,
                    meta: { requiredAuth: true }
                },
                {
                    path: '/change_password', name: 'change_password', component: ChangePassword,
                    meta: { requiredAuth: true }
                }

            ]
        },
        { path: '*', component: NotFound }
    ]
}

export default routes()
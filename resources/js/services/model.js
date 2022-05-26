export default {
    CREATE_DEPARTMENT_PAYLOAD: (department) => {
        const payload = (({
            id, name
        }) => ({
            id, name
        }))(department);
        return payload;
    },
    CREATE_COMPANY_PAYLOAD: (company) => {
        const payload = (({
            id, name, email, address
        }) => ({
            id, name, email, address
        }))(JSON.parse(JSON.stringify(company)));
        return payload;
    },
    CREATE_EMPLOYEE_PAYLOAD: (employee) => {
        const payload = (({
            id, first_name, last_name, email, company, department, role, phone, address
        }) => ({
            id, first_name, last_name, email, company_id: company.id, department_id: department.id, role_id: role.id, phone, address
        }))(JSON.parse(JSON.stringify(employee)));
        return payload;
    }
}

db.getSiblingDB('admin').createUser({ user: 'admin', pwd: 'password', roles: ['userAdminAnyDatabase'] });

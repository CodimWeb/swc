import { createRouter, createWebHistory } from 'vue-router'
import Home from '../views/Home/index.vue'

const routes = [
    {
        path: '/',
        name: 'home',
        component: Home
    },
    {
        path: '/registration',
        name: 'registration',
        component: () => import('../views/Auth/Registration.vue')
    },
    {
        path: '/login',
        name: 'login',
        component: () => import('../views/Auth/Login.vue')
    },
]

const router = createRouter({
    history: createWebHistory(),
    routes
})

router.beforeEach((to, from, next) => {
    const accessToken = localStorage.getItem('access_token');
    if(accessToken) {
        if(to.path === "/login" || to.path === "/registration") {
            return next({path: '/'})
        }
        next()
    }
    else {
        if(to.path === "/registration" || to.path === '/login') {
            next()
        }
        else {
            return next({path: '/login'})
        }
    }
})

export default router

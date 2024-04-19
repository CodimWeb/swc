<template>
    <Header />
    <div class="container">
        <div class="row">
            <div class="col-4">
                <h2 class="mb-3">Все события</h2>
                <ul class="list-group mb-5">
                    <li v-for="event in allEvents"
                        class="list-group-item"
                        :class="`${detailEvent?.id == event.id ? 'active': ''}`"
                        role="button"
                        :key="event.id"
                        @click="setDetailEvent(event)"
                    >
                        {{ event.title }}
                    </li>
                </ul>
                <h2 class="mb-3">Мои события</h2>
                <ul class="list-group">
                    <li v-for="event in myEvents"
                        class="list-group-item"
                        :class="`${detailEvent?.id == event.id ? 'active': ''}`"
                        role="button"
                        :key="event.id"
                        @click="setDetailEvent(event)"
                    >
                        {{ event.title }}
                    </li>
                </ul>
            </div>
            <div class="col-8">
                <h2 class="mb-2">Информация о евенте</h2>
                <div v-if="detailEvent?.id" class="card">
                    <div class="card-body">
                        <h3>{{ detailEvent.title }}</h3>
                        <p>{{ detailEvent.description }}</p>
                        <p>{{ new Intl.DateTimeFormat('ru-RU').format(new Date(detailEvent.date)) }}</p>
                        <div class="row">
                            <div class="col-12">
                                <h3>Участники</h3>
                            </div>
                            <div class="col-5">
                                <ul class="list-group mb-3">
                                    <li v-for="user in detailEvent.users"
                                        class="list-group-item"
                                        :class="`${user?.id == detailUser?.id ? 'active' : ''}`"
                                        role="button"
                                        @click="setDetailUser(user)"
                                    >
                                        {{ user.first_name }} {{ user.last_name }}
                                    </li>
                                </ul>
                                <button v-if="isUserMember()" class="btn btn-danger" @click="unsubscribeEvent(detailEvent?.id)">Отменить участие</button>
                                <button v-else class="btn btn-success" @click="subscribeEvent(detailEvent?.id)">Принять участие</button>
                                <button v-if="detailEvent.is_author" class="btn btn-danger" @click="deleteEvent(detailEvent?.id)">Удалить событие</button>
                            </div>
                            <div v-if="detailUser?.id" class="col-7">
                                <p>{{ detailUser.first_name }} {{ detailUser.last_name }}</p>
                                <p>{{ new Intl.DateTimeFormat('ru-RU').format(new Date(detailUser.birthday)) }} </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Header from "../../components/Header.vue";
import {onMounted, onUpdated, reactive, toRefs} from "vue";
import api from "../../api/index.js";
export default {
    name: 'Home',
    components: {
        Header
    },
    setup(props) {
        const state = reactive({
            allEvents: [],
            myEvents: [],
            detailEvent: {},
            detailUser: {},
            user: {},

        })

        onMounted(() => {
            fetchEvents()
            api.get('/api/auth/me').then((res) => {
                state.user = res.data.result.user
            })

            setInterval(() => {
                fetchEvents()
            }, 30000)
        })

        const splitEvents = (events) => {
            const myEvents = []
            const allEvents = []
            events.forEach((item) => {
                if(item.is_author) {
                    myEvents.push(item)
                }
                else {
                    allEvents.push(item)
                }
            })
            state.myEvents = myEvents
            state.allEvents = allEvents
        }

        const updateDetail = (events) => {
            let detailEvent = {}
            if(state.detailEvent?.id) {
                detailEvent = events.find(event => event.id == state.detailEvent.id)
                setDetailEvent(detailEvent)
            }
            if(state.detailUser?.id) {
                setDetailUser(detailEvent?.users.find(user => user.id == state.detailUser.id))
            }
        }

        const fetchEvents = () => {
            api.get('/api/events').then((res) => {
                splitEvents(res.data.result)
                updateDetail(res.data.result)
            })
        }

        const setDetailEvent = (event) => {
            state.detailEvent = event
            state.detailUser = null
        }

        const setDetailUser = (user) => {
            state.detailUser = user
        }

        const isUserMember = () => {
            return state.detailEvent?.users.find(user => user.id == state.user.id)
        }

        const subscribeEvent = (id) => {
            api.get(`/api/events/${id}/subscribe`).then((res) => {
                splitEvents(res.data.result)
                updateDetail(res.data.result)
            })
        }

        const unsubscribeEvent = (id) => {
            api.get(`/api/events/${id}/unsubscribe`).then((res) => {
                splitEvents(res.data.result)
                updateDetail(res.data.result)
            })
        }

        const deleteEvent = (id) => {
            api.delete(`/api/events/${id}`).then((res) => {
                splitEvents(res.data.result)
                state.detailEvent = {}
                state.detailUser = {}
            })
        }

        return {
            ...toRefs(state),
            setDetailEvent,
            setDetailUser,
            isUserMember,
            subscribeEvent,
            unsubscribeEvent,
            deleteEvent
        }
    }
}
</script>

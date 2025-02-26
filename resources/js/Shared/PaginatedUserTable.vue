<template>
    <div class="list-group" :class="paddingClass" v-if="data.data.length > 0">
        <div
            v-for="(item, index) in data.data"
            :key="item.id"
            class="list-group-item d-flex align-items-center py-3 px-4 rounded mb-2 shadow-sm"
            :class="{'active': selected.includes(item.id)}"
            @click="select(item.id)"
        >
            <div class="profile-picture-container me-3">
                <img
                    :src="item.profile_picture"
                    alt="Profile Picture"
                    class="profile-picture"
                />
            </div>

            <div class="flex-grow-1">
                <small class="mb-1 fw-bold h4" v-if="item.name">{{ item.name }}</small>
                <small class="mb-1 fw-bold h4" v-if="item.first_name && item.last_name">{{ item.first_name }} {{ item.last_name }}</small>
                <small>Age: {{ item.age }}</small>
                <small v-if="item.last_seen" class="small">Last seen: {{ item.last_seen }}</small>
            </div>

            <input type="radio" class="form-check-input" :checked="selected.includes(item.id)" @click.stop="select(item.id)" v-if="checkable" />
        </div>
    </div>

    <div class="text-center py-5" v-else>
        <p class="text-muted">No matching search results</p>
    </div>

    <div class="pagination-container d-flex justify-content-center align-items-center my-3">
        <ul class="pagination">
            <li class="page-item" v-for="(link) in links" :class="{'disabled': !link.url || link.active }">
                <button class="page-link" @click="load(link.url)" v-html="link.label"></button>
            </li>
        </ul>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: 'PaginatedUserTable',
    props: {
        data: {
            type: Object,
            default: null
        },
        paddingClass: {
            type: String,
            default: null
        },
        links: {
            type: Array,
            default: []
        },
        checkable: {
            type: Boolean,
            default: false
        },
    },
    data() {
        return {
            selected: [],
        };
    },
    computed: {
        paginationPages() {
            const currentPage = this.meta?.current_page || 1;
            const lastPage = this.meta?.last_page || 1;
            const delta = 2;
            const range = [];

            for (let i = Math.max(1, currentPage - delta); i <= Math.min(lastPage, currentPage + delta); i++) {
                range.push(i);
            }

            return range;
        },
    },
    methods: {
        load(url) {
            axios.get(url)
                .then(response => {
                    this.$emit('update-user-table-data', response.data);
                })
                .catch(error => {
                    console.error(error);
                });
        },
        select(id) {
            if (!this.checkable) return;

            if (this.selected.includes(id)) {
                this.selected = this.selected.filter((selectedId) => selectedId !== id);
            } else {
                this.selected.push(id);
            }

            this.$emit('update-selected', this.selected);
        }

    },
};
</script>

<style scoped>
.profile-picture-container {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    overflow: hidden;
    border: 2px solid #f0f0f0;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}

.profile-picture {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.list-group-item {
    background-color: #ffffff;
    border: 1px solid #eaeaea;
    border-radius: 0.5rem;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    padding: 1rem;
    margin-bottom: 0.5rem;
}

.list-group-item:hover {
    background: var(--blue) !important;
    color: #fff;
    transform: translateY(-2px);
}

.text-muted {
    font-size: 0.85rem;
}

.pagination-container button {
    min-width: 100px;
}

.pagination {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 0.5rem;
}

.page-item {
    display: inline;
}

.page-item.active .page-link {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}

.page-link {
    padding: 0.5rem 0.75rem;
    border: 1px solid #dee2e6;
    background-color: white;
    color: #007bff;
    cursor: pointer;
}

.page-link:hover {
    background-color: #e9ecef;
}

.page-item.disabled .page-link {
    cursor: not-allowed;
    opacity: 0.5;
}

.flex-grow-1 {
    margin: 0;
    display: flex;
    flex-direction: column;
    justify-content: center;
    gap: 0.25rem;
}

h6 {
    font-size: 1.4rem;
}

.text-muted {
    font-size: 1.2rem;
    line-height: 1.2;
}

.night-mode small {
    font-size: 1.2rem;
    color: #e9ecef;
}

.active {
    background-color: var(--blue) !important;
}

.active:hover {
    background-color: var(--blue) !important;
}
</style>

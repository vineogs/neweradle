<script setup>
import AttemptRow from './AttemptRow.vue'

defineProps({
    tentativas: {
        type: Array,
        default: () => []
    },
    titulo: {
        type: String,
        default: ''
    }
})
</script>

<template>
    <div>
        <h2 v-if="titulo" class="text-xl font-bold mb-3 text-center text-purple-300">
            {{ titulo }}
        </h2>

        <!-- HEADER -->
        <div class="grid grid-cols-10 gap-2 mb-2">

            <div class="cell header-cell">Img</div>
            <div class="cell header-cell">Nome</div>
            <div class="cell header-cell">Gênero</div>
            <div class="cell header-cell">Estado</div>
            <div class="cell header-cell">Idade</div>
            <div class="cell header-cell">Elemento</div>
            <div class="cell header-cell">Afiliação</div>
            <div class="cell header-cell">Aparição</div>
            <div class="cell header-cell">Cabelo</div>
            <div class="cell header-cell">Nação</div>

        </div>

        <div v-if="!tentativas.length" class="text-center text-gray-400">
            Nenhuma tentativa ainda
        </div>

        <div v-else class="flex flex-col gap-2">
            <AttemptRow v-for="(t, i) in tentativas" :key="i" :tentativa="t" />
        </div>

    </div>
</template>

<style scoped>
.cell {
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 8px;
    border-radius: 8px;
    word-break: break-word;
    overflow: hidden;
}

/* HEADER VISUAL */
.header-cell {
    background-color: #374151;
    /* igual ao bg-gray-700 */
    color: #d1d5db;
    /* text-gray-300 */
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}
</style>
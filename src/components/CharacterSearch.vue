<script setup>
import { ref, computed, watch } from 'vue'

const props = defineProps({
    personagens: Array,
    disabled: Boolean,
    tentativas: {
        type: Array,
        default: () => []
    }
})

watch(
    () => props.personagens,
    (novo) => {
    },
    { immediate: true }
)

const busca = ref('')
const selecionando = ref(false)

const idsUsados = computed(() =>
    new Set(
        props.tentativas
            .map(t => t?.personagem?.id)
            .filter(Boolean)
    )
)

const personagensFiltrados = computed(() => {
    if (!busca.value.trim() || selecionando.value) return []

    const termo = busca.value.toLowerCase()

    return props.personagens.filter(p => {
        const descricoes = Array.isArray(p.descricao)
            ? p.descricao
            : [p.descricao]

        return (
            p.nome.toLowerCase().includes(termo) ||
            descricoes.some(alias =>
                (alias ?? '').toLowerCase().includes(termo)
            )
        ) && !idsUsados.value.has(p.id)
    })
})

const emit = defineEmits(['select'])

function selecionar(personagem) {
    emit('select', personagem)
    busca.value = ''
    selecionando.value = false
}

function onEnter() {
    const sugestao = personagensFiltrados.value[0]
    if (sugestao) selecionar(sugestao)
}

function onInput() {
    selecionando.value = false
}
</script>

<template>
    <div class="flex flex-col gap-2">

        <input v-model="busca" @input="onInput" @keydown.enter.prevent="onEnter" :disabled="disabled" type="text"
            placeholder="Digite um personagem..."
            class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 disabled:bg-gray-800 disabled:text-gray-500 disabled:cursor-not-allowed" />

        <div v-if="personagensFiltrados.length" class="bg-gray-700 rounded-lg overflow-hidden">
            <button v-for="personagem in personagensFiltrados" :key="personagem.id" @click="selecionar(personagem)"
                class="w-full flex items-center gap-3 px-4 py-2 hover:bg-gray-600">
                <img :src="personagem.imagem" :alt="personagem.nome" loading="lazy"
                    class="w-16 h-16 rounded object-contain shrink-0">

                <div class="flex flex-col">
                    <div>
                        <span>
                            {{ personagem.nome }}
                        </span>
                    </div>
                    <div>
                        <span class="text-xs">
                            {{ Array.isArray(personagem.descricao)
                                ? personagem.descricao.join(' - ')
                                : personagem.descricao
                            }}
                        </span>
                    </div>
                </div>
            </button>
        </div>

    </div>
</template>
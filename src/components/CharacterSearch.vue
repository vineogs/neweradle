    <script setup>
    import { ref, computed } from 'vue'

    const props = defineProps({
        personagens: Array,
        venceu: Boolean
    })

    const emit = defineEmits(['select', 'try'])

    const busca = ref('')
    const selecionando = ref(false)

    const personagensFiltrados = computed(() => {
        if (!busca.value.trim() || selecionando.value) return []

        return props.personagens.filter(p =>
            p.nome.toLowerCase().includes(busca.value.toLowerCase())
        )
    })

    function selecionar(personagem) {
        busca.value = personagem.nome
        selecionando.value = true

        emit('select', personagem)
    }

    function tentar() {
        emit('try')
        busca.value = ''
    }

    function onInput() {
        selecionando.value = false
    }
</script>

    <template>
        <div :class="venceu ? 'hidden' : ''" class="flex flex-col gap-2">

            <input v-model="busca" @input="onInput" :disabled="venceu" type="text" placeholder="Digite um personagem..."
                class="w-full px-4 py-3 rounded-lg bg-gray-700 border border-gray-600 disabled:bg-gray-800 disabled:text-gray-500 disabled:cursor-not-allowed" />

            <div v-if="personagensFiltrados.length && !venceu" class="bg-gray-700 rounded-lg overflow-hidden">
                <button v-for="personagem in personagensFiltrados" :key="personagem.id" @click="selecionar(personagem)"
                    class="w-full text-left px-4 py-2 hover:bg-gray-600">
                    {{ personagem.nome }}
                </button>
            </div>

        </div>
    </template>
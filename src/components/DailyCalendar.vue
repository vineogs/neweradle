<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    historico: {
        type: Object,
        default: () => ({})
    }
})

const emit = defineEmits(['close'])

const hoje = new Date()

const mesAtual = ref(hoje.getMonth())
const anoAtual = ref(hoje.getFullYear())

const meses = [
    'Janeiro', 'Fevereiro', 'Março', 'Abril',
    'Maio', 'Junho', 'Julho', 'Agosto',
    'Setembro', 'Outubro', 'Novembro', 'Dezembro'
]

function diasNoMes(mes, ano) {
    return new Date(ano, mes + 1, 0).getDate()
}

function primeiroDiaSemana(mes, ano) {
    return new Date(ano, mes, 1).getDay()
}

const gridDias = computed(() => {
    const totalDias = diasNoMes(mesAtual.value, anoAtual.value)
    const offset = primeiroDiaSemana(mesAtual.value, anoAtual.value)

    const dias = []

    for (let i = 0; i < offset; i++) {
        dias.push(null)
    }

    const historico = props.historico || {}

    for (let d = 1; d <= totalDias; d++) {
        const dia = String(d).padStart(2, '0')
        const mes = String(mesAtual.value + 1).padStart(2, '0')
        const data = `${anoAtual.value}-${mes}-${dia}`

        dias.push({
            dia,
            data,
            status: historico[data] ?? null
        })
    }

    return dias
})

function getStatusClass(status) {
    if (status === 'win') return 'bg-green-500'
    if (status === 'lose') return 'bg-red-500'
    if (!status) return 'bg-gray-700'
}

function prevMes() {
    if (mesAtual.value === 0) {
        mesAtual.value = 11
        anoAtual.value--
    } else {
        mesAtual.value--
    }
}

function nextMes() {
    if (mesAtual.value === 11) {
        mesAtual.value = 0
        anoAtual.value++
    } else {
        mesAtual.value++
    }
}

function handleKeydown(event) {
    if (event.key === 'Escape') {
        emit('close')
    }
}

onMounted(() => {
    window.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown)
})</script>

<template>
    <div class="fixed inset-0 bg-black/70 flex items-center justify-center z-50" @click="emit('close')">
        <div class="bg-gray-900 p-6 rounded-xl w-105" @click.stop>

            <div class="flex justify-between items-center mb-4">
                <button @click="prevMes" class="px-2">◀</button>

                <h2 class="font-bold text-lg">
                    {{ meses[mesAtual] }} {{ anoAtual }}
                </h2>

                <button @click="nextMes" class="px-2">▶</button>
            </div>

            <div class="grid grid-cols-7 text-center text-xs text-gray-400 mb-2">
                <div>Dom</div>
                <div>Seg</div>
                <div>Ter</div>
                <div>Qua</div>
                <div>Qui</div>
                <div>Sex</div>
                <div>Sáb</div>
            </div>

            <div class="grid grid-cols-7 gap-2">
                <div v-for="(d, i) in gridDias" :key="i"
                    class="w-10 h-10 flex items-center justify-center rounded-lg text-xs font-bold"
                    :class="d ? getStatusClass(d.status) : 'bg-transparent'">
                    {{ d ? d.dia : '' }}
                </div>
            </div>

            <button class="w-full mt-4 py-2 bg-gray-700 rounded-lg" @click="emit('close')">
                Fechar
            </button>

        </div>

    </div>
</template>
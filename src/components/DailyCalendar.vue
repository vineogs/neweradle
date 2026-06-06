<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'

const props = defineProps({
    historico: {
        type: Object,
        default: () => ({})
    },
    days: {
        type: Object,
        default: () => ({})
    }
})

const emit = defineEmits(['close', 'replay'])

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

    const progresso = props.historico || {}
    const diasValidos = props.days || {}

    for (let i = 0; i < offset; i++) {
        dias.push(null)
    }

    for (let d = 1; d <= totalDias; d++) {
        const dia = String(d).padStart(2, '0')
        const mes = String(mesAtual.value + 1).padStart(2, '0')
        const data = `${anoAtual.value}-${mes}-${dia}`

        const exists = !!diasValidos[data]

        const entry = progresso[data]

        let status = 'locked'

        if (exists) {
            if (entry?.original?.win) {
                status = 'win'
            } else if (entry?.attempts?.length) {
                status = 'played'
            } else {
                status = 'available'
            }
        }

        dias.push({
            dia,
            data,
            entry,
            exists,
            status,
            tentativas: entry?.attempts?.length ?? 0
        })
    }


    return dias
})

function getStatusClass(status) {
    if (status === 'win') return 'bg-green-500'
    if (status === 'played') return 'bg-yellow-500'
    if (status === 'available') return 'bg-gray-600'
    if (status === 'locked') return 'bg-gray-800 opacity-30'

    return 'bg-gray-800'
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
                <button @click="prevMes" class="px-2 cursor-pointer">◀</button>

                <h2 class="font-bold text-lg">
                    {{ meses[mesAtual] }} {{ anoAtual }}
                </h2>

                <button @click="nextMes" class="px-2 cursor-pointer">▶</button>
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
                    class="w-10 h-10 flex items-center justify-center rounded-lg text-xs font-bold relative" :class="[
                        d ? getStatusClass(d.status) : 'bg-transparent',
                        d && !d.exists ? 'pointer-events-none' : '',
                        d ? 'cursor-pointer' : 'cursor-default'
                    ]" @click="() => {
                        if (d?.exists) {
                            emit('replay', {
                                date: d.data,
                                entry: d.entry
                            })
                            emit('close')
                        }
                    }">
                    {{ d ? d.dia : '' }}

                    <span v-if="d?.tentativas" class="absolute bottom-0 right-1 text-[9px] opacity-70">
                        {{ d.tentativas }}
                    </span>
                </div>
            </div>

            <button class="w-full mt-4 py-2 bg-gray-700 rounded-lg cursor-pointer" @click="emit('close')">
                Fechar
            </button>

        </div>

    </div>
</template>
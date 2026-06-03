<script setup>
defineProps({
    tentativa: Object
})
</script>

<template>
    <div class="grid grid-cols-9 gap-2 font-semibold">

        <div class="cell bg-gray-700" :class="tentativa.resultado.nome ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.nome }}
        </div>

        <div class="cell" :class="tentativa.resultado.genero ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.genero }}
        </div>

        <div class="cell" :class="tentativa.resultado.estadoAtual ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.estadoAtual }}
        </div>

        <div class="cell" :class="{
            'bg-green-600': tentativa.resultado.idade === 'igual',
            'bg-red-600': tentativa.resultado.idade !== 'igual'
        }">

            <span v-if="tentativa.resultado.idade === 'igual'">
                {{ tentativa.personagem.atributos.idade }}
            </span>

            <span v-else-if="tentativa.resultado.idade === 'mais_velho'">
                {{ tentativa.personagem.atributos.idade }} ↓
            </span>

            <span v-else>
                {{ tentativa.personagem.atributos.idade }} ↑
            </span>

        </div>

        <div class="cell" :class="tentativa.resultado.elemento ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.elemento }}
        </div>

        <div class="cell" :class="tentativa.resultado.afiliacao ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.afiliacao }}
        </div>

        <div class="cell" :class="tentativa.resultado.primeiraAparicao ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.primeiraAparicao }}
        </div>

        <div class="cell" :class="tentativa.resultado.cabelo ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.cabelo }}
        </div>

        <div class="cell" :class="tentativa.resultado.nacionalidade ? 'bg-green-600' : 'bg-red-600'">
            {{ tentativa.personagem.atributos.nacionalidade }}
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
</style>
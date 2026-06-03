import personagens from '../data/personagens.json'

export function getPersonagens() {
    return personagens
}

export function gerarPersonagem(modo = 'diario') {
    if (modo === 'diario') return gerarPersonagemDiario()
    return gerarPersonagemAleatorio()
}

export function gerarPersonagemDiario() {
    const hoje = new Date()
    const seed = hoje.getFullYear() * 10000 + (hoje.getMonth() + 1) * 100 + hoje.getDate()
    const index = seed % personagens.length
    return personagens[index]
}

export function gerarPersonagemAleatorio() {
    return personagens[Math.floor(Math.random() * personagens.length)]
}

export function comparar(tentativa, secreto) {
    return {
        nome: tentativa.nome === secreto.nome,

        genero: tentativa.atributos.genero === secreto.atributos.genero,
        estadoAtual: tentativa.atributos.estadoAtual === secreto.atributos.estadoAtual,

        idade: compararIdade(tentativa, secreto),

        elemento: tentativa.atributos.elemento === secreto.atributos.elemento,
        afiliacao: tentativa.atributos.afiliacao === secreto.atributos.afiliacao,
        primeiraAparicao: tentativa.atributos.primeiraAparicao === secreto.atributos.primeiraAparicao,
        cabelo: tentativa.atributos.cabelo === secreto.atributos.cabelo,
        nacionalidade: tentativa.atributos.nacionalidade === secreto.atributos.nacionalidade
    }
}

function compararIdade(tentativa, secreto) {
    const t = rankIdade[tentativa.atributos.idade]
    const s = rankIdade[secreto.atributos.idade]

    if (t === s) return 'igual'
    if (t > s) return 'mais_velho'
    return 'mais_novo'
}

const rankIdade = {
    "0-10": 1,
    "10-20": 2,
    "20-30": 3,
    "30-40": 4,
    "40+": 5
}

export function getDataHoje() {
    return new Date().toISOString().split('T')[0]
}

export function carregarHistorico() {
    const data = localStorage.getItem('neweradle-history')
    return data ? JSON.parse(data) : {}
}

export function salvarHistorico(hist) {
    localStorage.setItem('neweradle-history', JSON.stringify(hist))
}

export function setStatusDia(status) {
    const hist = carregarHistorico()
    const hoje = getDataHoje()

    hist[hoje] = status

    salvarHistorico(hist)
}

export function formatarDataChave(date = new Date()) {
    const ano = date.getFullYear()
    const mes = String(date.getMonth() + 1).padStart(2, '0')
    const dia = String(date.getDate()).padStart(2, '0')

    return `${ano}-${mes}-${dia}`
}
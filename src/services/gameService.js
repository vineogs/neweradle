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
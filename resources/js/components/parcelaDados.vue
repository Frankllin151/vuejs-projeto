<script setup>
import { ref , watch, reactive, computed } from 'vue';
import { usePage } from '@inertiajs/vue3'
import Modal from '@/components/ui/modal/Modal.vue'
import Button from './ui/button/Button.vue';
import Label from './ui/label/Label.vue';
import Input from './ui/input/Input.vue';
import { router } from '@inertiajs/vue3';
import Card from './ui/card/Card.vue';
import { watchEffect } from 'vue'
import { format, addMonths, parseISO } from 'date-fns'
const dados = usePage().props.dados;
const props = defineProps({
  dados: Array,
})


const editamodal = ref(false)
const form = reactive({
  venda_id: 0,
  total: 0,
  numero_parcela: 1,
  valor_primeira_parcela: 0, // NOVO
  data_vencimento: [],
})

watchEffect(() => {
  const totalParcelas = form.numero_parcela
  const dataInicial = form.data_vencimento?.[0]
  if (dataInicial && totalParcelas > 0) {
    const datas = []

    for (let i = 0; i < totalParcelas; i++) {
      const novaData = addMonths(parseISO(dataInicial), i)
      datas.push(format(novaData, 'yyyy-MM-dd'))
    }
    form.data_vencimento = datas
  }
})

const valoresParcelas = computed(() => {
  const total = form.total
  const primeiraParcela = form.valor_primeira_parcela || 0
  const numeroParcelas = form.numero_parcela

  if (numeroParcelas <= 0 || total <= 0) return []

  if (!form.valor_primeira_parcela) {
    const parcelaIgual = total / numeroParcelas
    const parcelas = Array.from({ length: numeroParcelas }, () =>
      parseFloat(parcelaIgual.toFixed(2))
    )

    const soma = parcelas.reduce((a, b) => a + b, 0)
    const diferenca = parseFloat((total - soma).toFixed(2))
    parcelas[parcelas.length - 1] += diferenca

    return parcelas
  }

  // Lógica com primeira parcela definida
  const restante = total - primeiraParcela
  const parcelasRestantes = numeroParcelas - 1

  if (parcelasRestantes <= 0) {
    return [parseFloat(primeiraParcela.toFixed(2))]
  }

  const parcelaBruta = restante / parcelasRestantes
  const parcelas = [parseFloat(primeiraParcela.toFixed(2))]

  for (let i = 0; i < parcelasRestantes; i++) {
    parcelas.push(parseFloat(parcelaBruta.toFixed(2)))
  }

  const soma = parcelas.reduce((a, b) => a + b, 0)
  const diferenca = parseFloat((total - soma).toFixed(2))
  parcelas[parcelas.length - 1] += diferenca

  return parcelas
})

const  editarVenda = (vendaId) =>{
  const item = dados.find((v) => v.venda_id === vendaId)
  form.total = parseFloat(item.venda.total)
  form.desconto = 0
  form.venda_id = vendaId
  form.numero_parcela = item.numero_parcela
  form.data_vencimento = [...item.data_vencimento]
  editamodal.value = true
}

const enviaredicao = ()  =>{
  const editavenda = {
    ...form,
    valor: valoresParcelas.value,
  }
  
  
  router.put(`/dashboard/parcelas/${form.venda_id}`,editavenda ,{
    onSuccess: () => {
      showModal.value = false
      produtoSelecionado.value = null
      
    },
    onError: (errors) => {
      console.error('Erro ao editar:', errors)
    }
  }); 
  fecharModalEditar()
}

const  fecharModalEditar =() =>  {
  editamodal.value = false
}
const excluirVenda = (id) =>{
  if (confirm("Tem certeza que excluir a parcelas?")) {
        router.delete(`/dashboard/parceladelete/${id}`, {
            onSuccess: () => {
                console.log('Produto excluído com sucesso');
            },
            onError: (errors) => {
                console.error('Erro ao excluir produto:', errors);
            }
        });
    }
}

</script>

<template>
  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
    <Card
      v-for="(item, index) in dados"
      :key="index"
      class="p-4 border rounded shadow flex flex-col gap-2"
    >
      <div><strong>Nome:</strong> {{ item.venda.nome_cliente }}</div>
      <div><strong>Email:</strong> {{ item.venda.email_cliente }}</div>

      <div>
        <strong>Data Vencimento:</strong>
        <ul>
          <li v-for="(data, i) in item.data_vencimento" :key="i">{{ data }}</li>
        </ul>
      </div>

      <div>
        <strong>Valor:</strong>
        <ul>
          <li v-for="(valor, i) in item.valor" :key="i">R$ {{ parseFloat(valor).toFixed(2) }}</li>
        </ul>
      </div>

      <div><strong>Quantidade de Parcelas:</strong> {{ item.numero_parcela }}</div>
      <div><strong>Total:</strong> R$ {{ parseFloat(item.venda.total).toFixed(2) }}</div>

      <div class="flex gap-2 mt-2">
        <Button
          class="bg-blue-500 text-white px-3 py-1 rounded"
          @click="editarVenda(item.venda_id)"
        >
          Editar
        </Button>
        <Button
          class="bg-red-500 text-white px-3 py-1 rounded"
          @click="excluirVenda(item.venda_id)"
        >
          Excluir
        </Button>
      </div>
    </Card>
  </div>

  <Modal :show="editamodal">
  <form @submit.prevent="enviaredicao" class="p-4 space-y-4">
 
    <div>
      <label class="block text-sm font-medium">Total (R$)</label>
      <input
        v-model.number="form.total"
        type="number"
        step="0.01"
        class="w-full border p-2 rounded"
      />
    </div>
    <div>
  <label class="block text-sm font-medium">Valor da Primeira Parcela (R$)</label>
  <input
    v-model.number="form.valor_primeira_parcela"
    type="number"
    step="0.01"
    class="w-full border p-2 rounded"
  />
</div>

 
    <div>
      <label class="block text-sm font-medium">Número de Parcelas</label>
      <input
        v-model.number="form.numero_parcela"
        type="number"
        class="w-full border p-2 rounded"
      />
    </div>

 
    <div>
      <label class="block text-sm font-medium">Valores das Parcelas</label>
      <ul>
        <li v-for="(valor, index) in valoresParcelas" :key="index">
          Parcela {{ index + 1 }}: R$ {{ valor.toFixed(2) }}
        </li>
      </ul>
    </div>

    <!-- Botões -->
    <div class="flex justify-end gap-2 mt-6">
      <Button type="submit">Editar</Button>
      <Button type="button" @click="fecharModalEditar">Fechar</Button>
    </div>
  </form>
</Modal>
</template>

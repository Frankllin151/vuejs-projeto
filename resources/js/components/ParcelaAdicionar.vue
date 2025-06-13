<script setup>
import { ref, reactive, computed, watchEffect } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import Modal from '@/components/ui/modal/Modal.vue';
import Button from './ui/button/Button.vue';
import Label from './ui/label/Label.vue';
import Input from './ui/input/Input.vue';
import { format, addMonths, parseISO } from 'date-fns';

const vendas = usePage().props.vendasAll;
const ShowCreatemodal = ref(false);

const createAbriModal = () => ShowCreatemodal.value = true;
const fecharModalCreate = () => ShowCreatemodal.value = false;

const form = reactive({
  venda_id: null,
  total: 0,
  numero_parcela: 1,
  valor_primeira_parcela: 0,
  data_vencimento: '', // Data da 1ª parcela
  datas_vencimento: [] // Datas de todas as parcelas
});

// Atualiza o total da venda ao selecionar
const atualizarTotal = () => {
  const vendaSelecionada = vendas.find(v => v.id === form.venda_id);
  form.total = vendaSelecionada ? parseFloat(vendaSelecionada.total) : 0;
};

// Gera automaticamente as datas de vencimento
watchEffect(() => {
  const totalParcelas = form.numero_parcela;
  const dataInicial = form.data_vencimento;
  if (dataInicial && totalParcelas > 0) {
    const datas = [];
    for (let i = 0; i < totalParcelas; i++) {
      const novaData = addMonths(parseISO(dataInicial), i);
      datas.push(format(novaData, 'yyyy-MM-dd'));
    }
    form.datas_vencimento = datas;
  }
});

// Calcula os valores das parcelas
const valoresParcelas = computed(() => {
  const total = form.total;
  const primeiraParcela = form.valor_primeira_parcela || 0;
  const numeroParcelas = form.numero_parcela;

  if (numeroParcelas <= 0 || total <= 0) return [];

  if (!form.valor_primeira_parcela) {
    const parcelaIgual = total / numeroParcelas;
    const parcelas = Array.from({ length: numeroParcelas }, () =>
      parseFloat(parcelaIgual.toFixed(2))
    );
    const soma = parcelas.reduce((a, b) => a + b, 0);
    const diferenca = parseFloat((total - soma).toFixed(2));
    parcelas[parcelas.length - 1] += diferenca;
    return parcelas;
  }

  const restante = total - primeiraParcela;
  const parcelasRestantes = numeroParcelas - 1;

  if (parcelasRestantes <= 0) return [parseFloat(primeiraParcela.toFixed(2))];

  const parcelaBruta = restante / parcelasRestantes;
  const parcelas = [parseFloat(primeiraParcela.toFixed(2))];
  for (let i = 0; i < parcelasRestantes; i++) {
    parcelas.push(parseFloat(parcelaBruta.toFixed(2)));
  }
  const soma = parcelas.reduce((a, b) => a + b, 0);
  const diferenca = parseFloat((total - soma).toFixed(2));
  parcelas[parcelas.length - 1] += diferenca;

  return parcelas;
});

// Envia os dados para criação
const enviarFormulario = () => {
  const parcelas = valoresParcelas.value.map((valor, index) => ({
    venda_id: form.venda_id,
    numero_parcela: index + 1,
    data_vencimento: form.datas_vencimento[index],
    valor: valor
  }));
console.log(parcelas);

 router.post('/dashboard/postnovaparcela', parcelas, {
    onSuccess: () => {
      fecharModalCreate();
    },
    onError: (errors) => {
      console.error('Erro ao enviar:', errors);
    }
  });
};
</script>

<template>
  <div class="w-full">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Parcela</h1>
      <Button 
        @click="createAbriModal"
        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
      >
        Nova parcela
      </Button>
    </div>
  </div>

  <Modal :show="ShowCreatemodal">
    <form @submit.prevent="enviarFormulario" class="space-y-4 max-w-md p-4">
      <div>
        <Label>Venda</Label>
        <select 
          v-model="form.venda_id"
          @change="atualizarTotal"
          class="w-full p-2 border rounded bg-black text-white"
          required
        >
          <option value="" disabled>Selecione uma venda</option>
          <option v-for="venda in vendas" :key="venda.id" :value="venda.id">
            #{{ venda.id }} - {{ venda.nome_cliente }} - R$ {{ venda.total }}
          </option>
        </select>
      </div>

      <div>
        <Label>Total da Venda</Label>
        <Input type="number" v-model="form.total" readonly class="bg-gray-100" />
      </div>

    

      <div>
        <Label>Número de Parcelas</Label>
        <Input type="number" min="1" v-model.number="form.numero_parcela" required />
      </div>
  <div>
        <Label>Valor da Primeira Parcela (opcional)</Label>
        <Input type="number" step="0.01" v-model.number="form.valor_primeira_parcela" />
      </div>
      <div>
        <Label>Data da 1ª Parcela</Label>
        <Input type="date" v-model="form.data_vencimento" required />
      </div>

      <div>
        <Label>Parcelas</Label>
        <ul class="text-sm  p-2 rounded">
          <li v-for="(valor, index) in valoresParcelas" :key="index">
            Parcela {{ index + 1 }}: R$ {{ valor.toFixed(2) }} - {{ form.datas_vencimento[index] || '...' }}
          </li>
        </ul>
      </div>

      <div class="flex justify-end gap-2 pt-4">
        <Button type="submit" class="bg-green-600 hover:bg-green-700 text-white">Gerar Parcelas</Button>
        <Button type="button" class="bg-gray-400 hover:bg-gray-500 text-white" @click="fecharModalCreate">Fechar</Button>
      </div>
    </form>
  </Modal>
</template>

<script setup>
import { ref , watch } from 'vue';
import { usePage } from '@inertiajs/vue3'
import Modal from '@/components/ui/modal/Modal.vue'
import Button from './ui/button/Button.vue';
import Label from './ui/label/Label.vue';
import Input from './ui/input/Input.vue';
import { router } from '@inertiajs/vue3';
const data = usePage().props.data;
const produtoall = usePage().props.produtoall;


const showModalAdicionar = ref(false)
const form = ref({
  nome_cliente: '',
  email_cliente: '',
  quantidade: 1,
  produto_id: '',
  total: 0
});

const vendas = ref(data); 



const formatarPreco = (valor) => {
    return Number(valor).toLocaleString('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    });
};
const excluirVenda = (id, nome) => {
    if (confirm(`Deseja excluir a venda de ${nome}?`)) {
      router.delete(`/dashboard/venda/${id}/delete`,{
        onSuccess: () => {
                alert('Produto excluído com sucesso');
            },
            onError: (errors) => {
                console.error('Erro ao excluir produto:', errors);
                alert("Deu algum error")
            }
      })
        

    
    }
};

watch(() => form.value.produto_id, () => {
  calcularTotal();
});

// Atualiza o total quando a quantidade mudar
watch(() => form.value.quantidade, () => {
  calcularTotal();
});

const calcularTotal = () => {
  const produtoSelecionado = produtoall.find(p => p.id === form.value.produto_id);

 const preco = parseFloat(produtoSelecionado.preco);
    form.value.total = (form.value.quantidade * preco).toFixed(2);


};

const enviarNovaVenda= () => {
  console.log('Cliente a ser adicionado:', form.value);
  router.post('/dashboard/novavenda', form.value)
  showModalAdicionar.value = false;
};
const abrirModal = () => {
  showModalAdicionar.value = true 
}
const fecharModalAdicionar = ()=> {
  showModalAdicionar.value = false
}
</script>

<template>
    <div class="shadow-sm rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
          <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold ">Vendas</h1>
            <button 
               @click="abrirModal"
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-medium transition-colors"
            >
                Nova Venda
            </button>
        </div>
           <div v-if="data.length === 0" class="text-center py-8">
            <p class="text-gray-500 text-lg">Nenhuma venda encontrada.</p>
        </div>

            <table v-else class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-mail</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qtd</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="venda in vendas" :key="venda.id">
                        <td class="px-6 py-4 text-sm font-medium">{{ venda.id }}</td>
                        <td class="px-6 py-4 text-sm">{{ venda.nome_cliente }}</td>
                        <td class="px-6 py-4 text-sm">{{ venda.email_cliente }}</td>
                        <td class="px-6 py-4 text-sm">{{ venda.quantidade }}</td>
                        <td class="px-6 py-4 text-sm font-medium">{{ formatarPreco(venda.total) }}</td>
                        <td class="px-6 py-4 text-right text-sm font-medium">
                            <div class="flex justify-end space-x-2">
                                
                                <button
                                    @click="excluirVenda(venda.id, venda.nome_cliente)"
                                    class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-xs font-medium transition-colors"
                                >
                                    Excluir
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>


   
    <Modal :show="showModalAdicionar">
  <form @submit.prevent="enviarNovaVenda">
    <div class="space-y-4">
      <div>
        <Label class="block text-sm font-medium">Nome do Cliente</Label>
        <Input type="text" v-model="form.nome_cliente" class="mt-1 block w-full border rounded-md p-2" required />
      </div>

      <div>
        <Label class="block text-sm font-medium">Email do Cliente</Label>
        <Input type="email" v-model="form.email_cliente" class="mt-1 block w-full border rounded-md p-2"  required />
      </div>

      <div>
        <label class="block text-sm font-medium">Produto</label>
        <select v-model="form.produto_id" class="bg-black text-white mt-1 block w-full border rounded-md p-2"  required >
          <option disabled value="">Selecione um produto</option>
          <option v-for="produto in produtoall" :key="produto.id" :value="produto.id">
            {{ produto.nome }} - R$ {{ produto.preco }}
          </option>
        </select>
      </div>

      <div>
        <Label class="block text-sm font-medium">Quantidade</Label>
        <Input type="number"  required  v-model="form.quantidade" min="1" class="mt-1 block w-full border rounded-md p-2" />
      </div>

      <div>
        <Label class="block text-sm font-medium">Total</Label>
        <Input type="number"  required  v-model="form.total" readonly step="0.01" class="mt-1 block w-full border p-2" />
      </div>
    </div>

    <div class="flex justify-end gap-2 mt-6">
      <Button type="submit">Adicionar</Button>
      <Button type="button" @click="fecharModalAdicionar">Fechar</Button>
    </div>
  </form>
</Modal>
  
</template>

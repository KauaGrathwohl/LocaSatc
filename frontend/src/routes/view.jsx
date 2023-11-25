import React from 'react';
import NotebookDell from '../../public/images/notebook_dell.jpg';

export default function View() {
  return (
    <div className="font-sans">
      <header className="bg-gray-800 text-white mb-12 text-center py-4">
        
        <h1 className="text-2xl">Notebook DELL </h1>
      </header>

      <section className="flex justify-around p-4">
        <div className="max-w-md">
          <img src={NotebookDell} alt="Produto Azul" className="max-w-full h-auto border border-gray-300 p-1" />
        </div>

        <div className="max-w-md">
          <h2 className="text-xl text-white font-semibold mb-4">Especificações Técnicas</h2>
          <ul className="list-disc text-white pl-4 mb-4">
            <li>Resolução: 1920x1080</li>
            <li>Processador: Quad-Core</li>
            <li>Armazenamento: 256GB SSD</li>
            <li>Câmera: Câmera HD de 720p</li>
          </ul>

          <div className='flex flex-col'>/ 
          <h2 className="text-xl text-white font-semibold mb-4">Selecione a Cor</h2>
          <select id="corProduto" className="mb-4">
            <option value="azul">Cinza escuro</option>
            <option value="preto">Preto</option>
            <option value="branco">Branco</option>
            <option value="branco">Branco</option>
            <option value="branco">Cinza</option>
          </select>
          <h2 className="text-xl text-white font-semibold mb-4">R$ 2.500,00</h2>

          <button onClick={null} className="bg-red-500 text-white py-2 px-4 rounded">Adicionar ao Carrinho</button>
          </div>
        </div>
        <div className='max-w-md'>
          <ul className="list-disc text-white pl-4 mb-4">
          </ul>
        </div>
        
      </section>
    </div>
  );
}


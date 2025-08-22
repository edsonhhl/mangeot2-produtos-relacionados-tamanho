# mangeot2-produtos-relacionados-tamanho

Dot_ProductSizes

Atributo de produtos relacionados por tamanho/volume SKU|Rótulo (ex: 1234|500ml).

## Instalação
1. Copie `app/code/Dot/ProductSizes` para o seu projeto.
2. Rode:
   ```bash
   bin/magento setup:upgrade
   bin/magento cache:flush
   ```
3. No produto, preencha o atributo **Produtos Relacionados (Tamanhos/Volumes)** com SKU | Tamanho.

### Como funciona
- Backend: Adiciono o SKU | Tamanho, separado por linha exemplo:. 
**1234|300g**
**2234|5kg**
**334455|1kg**
- Frontend: exibe o rotulo com o tamanho e no hover o Produto + Imagem + Preço.
- Renderiza botões clicáveis logo abaixo do preço.

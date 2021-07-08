# Verifica vagas para vacinação de covid-19 na PMV

Script em PHP que verifica número de vagas no site de agendamento da Prefeitura Municipal de Vitória e gera dialog quando encontra vaga em alguma unidade

## Funcionamento

Script consulta a API e verifica número de vagas.
Caso seja encontrada alguma vaga em qualquer unidade é gerado um dialog para o Finder.

![Screen Shot 2021-07-08 at 14 40 11](https://user-images.githubusercontent.com/2111143/124967819-3db52880-dffb-11eb-8475-996ad9d971ac.png)

### Configurações

Um parâmetro é aceito para configurar o serviço de agendamento.

Exemplo: 1476 é o valor padrão, que corresponde a vacinação de 30 a 34 anos.

Você pode verificar no código do site de agendamento da PMV.

### Histórico

Você pode colocar o script para funcionar nas tarefas agendadas (cron) de forma a realizar periodicamente (por exemplo, de 1 em 1 minuto).

![Screen Shot 2021-07-08 at 14 27 09](https://user-images.githubusercontent.com/2111143/124967855-44dc3680-dffb-11eb-890c-1b5839746421.png)

Exemplo de configuração para cron:

```
* * * * * /usr/local/bin/php ~/vagas-covid/index.php >> ~/verifica-vagas.txt
```

## Requerimentos

- Mac OS
- PHP 7.0 ou superior

## Disclaimer

Script foi feito em caráter de teste e não tem qualquer fim comercial.
Não me responsabilizo sobre qualquer forma de utilização do mesmo, indevida ou não. 
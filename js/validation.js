(function () {
  'use strict';

  // ── helpers ──────────────────────────────────────────────────────
  function showErr(el, msg) {
    el.classList.add('is-invalid');
    const fb = el.nextElementSibling && el.nextElementSibling.classList.contains('invalid-feedback')
      ? el.nextElementSibling
      : el.parentElement.querySelector('.invalid-feedback');
    if (fb) fb.textContent = msg;
  }

  function clearErr(el) {
    el.classList.remove('is-invalid');
  }

  function clearAll(form) {
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
  }

  function fld(form, name) {
    return form.querySelector('[name="' + name + '"]');
  }

  // ── validators ───────────────────────────────────────────────────
  function validCPF(cpf) {
    cpf = cpf.replace(/\D/g, '');
    if (cpf.length !== 11 || /^(\d)\1{10}$/.test(cpf)) return false;
    let s = 0;
    for (let i = 0; i < 9; i++) s += parseInt(cpf[i]) * (10 - i);
    let r = (s * 10) % 11;
    if (r === 10 || r === 11) r = 0;
    if (r !== parseInt(cpf[9])) return false;
    s = 0;
    for (let i = 0; i < 10; i++) s += parseInt(cpf[i]) * (11 - i);
    r = (s * 10) % 11;
    if (r === 10 || r === 11) r = 0;
    return r === parseInt(cpf[10]);
  }

  function validCNPJ(cnpj) {
    cnpj = cnpj.replace(/\D/g, '');
    if (cnpj.length !== 14 || /^(\d)\1{13}$/.test(cnpj)) return false;
    let len = 12, sum = 0, pos = 5;
    for (let i = 0; i < len; i++) {
      sum += parseInt(cnpj[i]) * pos--;
      if (pos < 2) pos = 9;
    }
    let r = sum % 11 < 2 ? 0 : 11 - (sum % 11);
    if (r !== parseInt(cnpj[12])) return false;
    len = 13; sum = 0; pos = 6;
    for (let i = 0; i < len; i++) {
      sum += parseInt(cnpj[i]) * pos--;
      if (pos < 2) pos = 9;
    }
    r = sum % 11 < 2 ? 0 : 11 - (sum % 11);
    return r === parseInt(cnpj[13]);
  }

  function validEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/.test(email);
  }

  function validCEP(cep) {
    return /^\d{5}-\d{3}$/.test(cep);
  }

  function validUF(uf) {
    return /^[A-Za-z]{2}$/.test(uf);
  }

  function validPlaca(placa) {
    const p = placa.replace(/-/g, '').toUpperCase();
    return /^[A-Z]{3}\d[A-Z0-9]\d{2}$/.test(p) || /^[A-Z]{3}\d{4}$/.test(p);
  }

  // ── input masks ──────────────────────────────────────────────────
  function maskCPF(v) {
    v = v.replace(/\D/g, '').slice(0, 11);
    if (v.length > 9) return v.replace(/(\d{3})(\d{3})(\d{3})(\d{1,2})/, '$1.$2.$3-$4');
    if (v.length > 6) return v.replace(/(\d{3})(\d{3})(\d{0,3})/, '$1.$2.$3');
    if (v.length > 3) return v.replace(/(\d{3})(\d{0,3})/, '$1.$2');
    return v;
  }

  function maskCNPJ(v) {
    v = v.replace(/\D/g, '').slice(0, 14);
    if (v.length > 12) return v.replace(/(\d{2})(\d{3})(\d{3})(\d{4})(\d{1,2})/, '$1.$2.$3/$4-$5');
    if (v.length > 8)  return v.replace(/(\d{2})(\d{3})(\d{3})(\d{0,4})/, '$1.$2.$3/$4');
    if (v.length > 5)  return v.replace(/(\d{2})(\d{3})(\d{0,3})/, '$1.$2.$3');
    if (v.length > 2)  return v.replace(/(\d{2})(\d{0,3})/, '$1.$2');
    return v;
  }

  function maskCEP(v) {
    v = v.replace(/\D/g, '').slice(0, 8);
    if (v.length > 5) return v.replace(/(\d{5})(\d{0,3})/, '$1-$2');
    return v;
  }

  function maskPhone(v) {
    v = v.replace(/\D/g, '').slice(0, 11);
    if (v.length > 10) return v.replace(/(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
    if (v.length > 6)  return v.replace(/(\d{2})(\d{4,5})(\d{0,4})/, '($1) $2-$3');
    if (v.length > 2)  return v.replace(/(\d{2})(\d{0,5})/, '($1) $2');
    return v;
  }

  function applyMask(form, name, maskFn) {
    const el = fld(form, name);
    if (!el) return;
    el.addEventListener('input', function () {
      const start = this.selectionStart;
      const prevRaw = this.value.slice(0, start).replace(/\D/g, '').length;
      this.value = maskFn(this.value);
      let newPos = 0, count = 0;
      for (let i = 0; i < this.value.length && count < prevRaw; i++) {
        if (/\d/.test(this.value[i])) count++;
        newPos = i + 1;
      }
      try { this.setSelectionRange(newPos, newPos); } catch(e) {}
    });
  }

  // ── reusable checks ──────────────────────────────────────────────
  function chkPhone(form, name) {
    const el = fld(form, name);
    if (!el || !el.value.trim()) return true;
    if (el.value.replace(/\D/g, '').length < 10) {
      showErr(el, 'Telefone inválido. Mínimo 10 dígitos.'); return false;
    }
    clearErr(el); return true;
  }

  function chkMaxlen(form, name, max, label) {
    const el = fld(form, name);
    if (!el || !el.value.trim()) return true;
    if (el.value.trim().length > max) {
      showErr(el, label + ' máx. ' + max + ' caracteres.'); return false;
    }
    clearErr(el); return true;
  }

  function chkRange(form, name, min, max, label) {
    const el = fld(form, name);
    if (!el || el.value === '') return true;
    const v = +el.value;
    if (v < min || v > max) {
      showErr(el, label + ' deve ser entre ' + min + ' e ' + max + '.'); return false;
    }
    clearErr(el); return true;
  }

  function chkRequired(form, name, label) {
    const el = fld(form, name);
    if (!el) return true;
    if (!el.value.trim()) { showErr(el, label + ' é obrigatório.'); return false; }
    clearErr(el); return true;
  }

  function chkSelect(form, name, label) {
    const el = fld(form, name);
    if (!el) return true;
    if (!el.value) { showErr(el, 'Selecione ' + label + '.'); return false; }
    clearErr(el); return true;
  }

  function chkEnum(form, name, allowed, label) {
    const el = fld(form, name);
    if (!el) return true;
    if (!allowed.includes(el.value)) { showErr(el, label + ' inválido.'); return false; }
    clearErr(el); return true;
  }

  function chkDateMin(form, name, minDateVal, label, minLabel) {
    const el = fld(form, name);
    if (!el || !el.value) return true;
    if (el.value < minDateVal) {
      showErr(el, label + ' não pode ser anterior a ' + minLabel + '.'); return false;
    }
    clearErr(el); return true;
  }

  function chkPositive(form, name, label) {
    const el = fld(form, name);
    if (!el || el.value === '') return true;
    if (parseFloat(el.value) <= 0) { showErr(el, label + ' deve ser maior que zero.'); return false; }
    clearErr(el); return true;
  }

  function chkNonNeg(form, name, label) {
    const el = fld(form, name);
    if (!el || el.value === '') return true;
    if (parseFloat(el.value) < 0) { showErr(el, label + ' não pode ser negativo.'); return false; }
    clearErr(el); return true;
  }

  // ── forma: marca ──────────────────────────────────────────────────
  const frmMarca = document.getElementById('frmMarca');
  if (frmMarca) {
    frmMarca.addEventListener('submit', function (e) {
      clearAll(this); let ok = true;
      if (!chkRequired(this, 'nome', 'Nome')) ok = false;
      if (!chkMaxlen(this, 'pais_origem', 100, 'País de origem')) ok = false;
      if (!ok) e.preventDefault();
    });
  }

  // ── form: categoria ──────────────────────────────────────────────
  const frmCategoria = document.getElementById('frmCategoria');
  if (frmCategoria) {
    frmCategoria.addEventListener('submit', function (e) {
      clearAll(this); let ok = true;
      if (!chkRequired(this, 'nome', 'Nome')) ok = false;
      else if (!chkMaxlen(this, 'nome', 100, 'Nome')) ok = false;
      const codEl = fld(this, 'codigo');
      if (!codEl.value.trim()) { showErr(codEl, 'Código é obrigatório.'); ok = false; }
      else if (codEl.value.trim().length > 20) { showErr(codEl, 'Código máx. 20 caracteres.'); ok = false; }
      else clearErr(codEl);
      if (!ok) e.preventDefault();
    });
  }

  // ── form: agencia ────────────────────────────────────────────────
  const frmAgencia = document.getElementById('frmAgencia');
  if (frmAgencia) {
    applyMask(frmAgencia, 'cnpj', maskCNPJ);
    applyMask(frmAgencia, 'cep',  maskCEP);
    applyMask(frmAgencia, 'telefone', maskPhone);

    frmAgencia.addEventListener('submit', function (e) {
      clearAll(this); let ok = true;
      if (!chkRequired(this, 'nome', 'Nome')) ok = false;
      else if (!chkMaxlen(this, 'nome', 150, 'Nome')) ok = false;
      if (!chkMaxlen(this, 'endereco', 255, 'Endereço')) ok = false;
      if (!chkMaxlen(this, 'cidade', 100, 'Cidade')) ok = false;
      if (!chkPhone(this, 'telefone')) ok = false;

      const cnpjEl = fld(this, 'cnpj');
      if (!cnpjEl.value.trim()) { showErr(cnpjEl, 'CNPJ é obrigatório.'); ok = false; }
      else if (!validCNPJ(cnpjEl.value)) { showErr(cnpjEl, 'CNPJ inválido. Use XX.XXX.XXX/XXXX-XX.'); ok = false; }
      else clearErr(cnpjEl);

      const emailEl = fld(this, 'email');
      if (emailEl && emailEl.value.trim() && !validEmail(emailEl.value.trim())) {
        showErr(emailEl, 'E-mail inválido.'); ok = false;
      } else if (emailEl) clearErr(emailEl);

      const cepEl = fld(this, 'cep');
      if (cepEl && cepEl.value.trim() && !validCEP(cepEl.value.trim())) {
        showErr(cepEl, 'CEP inválido. Use XXXXX-XXX.'); ok = false;
      } else if (cepEl) clearErr(cepEl);

      const ufEl = fld(this, 'estado');
      if (ufEl && ufEl.value.trim() && !validUF(ufEl.value.trim())) {
        showErr(ufEl, 'UF deve ter 2 letras.'); ok = false;
      } else if (ufEl) clearErr(ufEl);

      if (!ok) e.preventDefault();
    });
  }

  // ── form: funcionario ────────────────────────────────────────────
  const frmFuncionario = document.getElementById('frmFuncionario');
  if (frmFuncionario) {
    applyMask(frmFuncionario, 'cpf', maskCPF);
    applyMask(frmFuncionario, 'telefone', maskPhone);

    frmFuncionario.addEventListener('submit', function (e) {
      clearAll(this); let ok = true;
      if (!chkRequired(this, 'nome', 'Nome')) ok = false;

      const cpfEl = fld(this, 'cpf');
      if (!cpfEl.value.trim()) { showErr(cpfEl, 'CPF é obrigatório.'); ok = false; }
      else if (!validCPF(cpfEl.value)) { showErr(cpfEl, 'CPF inválido. Verifique os dígitos.'); ok = false; }
      else clearErr(cpfEl);

      if (!chkSelect(this, 'id_agencia', 'a agência')) ok = false;
      if (!chkMaxlen(this, 'cargo', 80, 'Cargo')) ok = false;
      if (!chkPhone(this, 'telefone')) ok = false;

      const emailEl = fld(this, 'email');
      if (emailEl && emailEl.value.trim() && !validEmail(emailEl.value.trim())) {
        showErr(emailEl, 'E-mail inválido.'); ok = false;
      } else if (emailEl) clearErr(emailEl);

      if (!ok) e.preventDefault();
    });
  }

  // ── form: cliente ────────────────────────────────────────────────
  const frmCliente = document.getElementById('frmCliente');
  if (frmCliente) {
    applyMask(frmCliente, 'cpf', maskCPF);
    applyMask(frmCliente, 'cep', maskCEP);
    applyMask(frmCliente, 'telefone', maskPhone);

    frmCliente.addEventListener('submit', function (e) {
      clearAll(this); let ok = true;
      if (!chkRequired(this, 'nome', 'Nome')) ok = false;
      else if (!chkMaxlen(this, 'nome', 150, 'Nome')) ok = false;
      if (!chkPhone(this, 'telefone')) ok = false;
      if (!chkMaxlen(this, 'endereco', 255, 'Endereço')) ok = false;
      if (!chkMaxlen(this, 'cidade', 100, 'Cidade')) ok = false;

      const cpfEl = fld(this, 'cpf');
      if (!cpfEl.value.trim()) { showErr(cpfEl, 'CPF é obrigatório.'); ok = false; }
      else if (!validCPF(cpfEl.value)) { showErr(cpfEl, 'CPF inválido. Verifique os dígitos.'); ok = false; }
      else clearErr(cpfEl);

      const cnhEl = fld(this, 'cnh');
      if (!cnhEl.value.trim()) { showErr(cnhEl, 'CNH é obrigatória.'); ok = false; }
      else if (cnhEl.value.trim().length > 20) { showErr(cnhEl, 'CNH máx. 20 caracteres.'); ok = false; }
      else clearErr(cnhEl);

      const vencCnhEl = fld(this, 'vencimento_cnh');
      if (vencCnhEl) {
        const today = new Date().toISOString().slice(0, 10);
        if (vencCnhEl.value && vencCnhEl.value < today) {
          showErr(vencCnhEl, 'CNH vencida. Verifique a data de vencimento.'); ok = false;
        } else clearErr(vencCnhEl);
      }

      const emailEl = fld(this, 'email');
      if (emailEl && emailEl.value.trim() && !validEmail(emailEl.value.trim())) {
        showErr(emailEl, 'E-mail inválido.'); ok = false;
      } else if (emailEl) clearErr(emailEl);

      const cepEl = fld(this, 'cep');
      if (cepEl && cepEl.value.trim() && !validCEP(cepEl.value.trim())) {
        showErr(cepEl, 'CEP inválido. Use XXXXX-XXX.'); ok = false;
      } else if (cepEl) clearErr(cepEl);

      const ufEl = fld(this, 'estado');
      if (ufEl && ufEl.value.trim() && !validUF(ufEl.value.trim())) {
        showErr(ufEl, 'UF deve ter 2 letras.'); ok = false;
      } else if (ufEl) clearErr(ufEl);

      if (!ok) e.preventDefault();
    });
  }

  // ── form: veiculo ────────────────────────────────────────────────
  const frmVeiculo = document.getElementById('frmVeiculo');
  if (frmVeiculo) {
    frmVeiculo.addEventListener('submit', function (e) {
      clearAll(this); let ok = true;
      if (!chkRequired(this, 'modelo', 'Modelo')) ok = false;
      if (!chkSelect(this, 'id_marca', 'a marca')) ok = false;
      if (!chkSelect(this, 'id_categoria', 'a categoria')) ok = false;

      const currYear = new Date().getFullYear();
      const anoFabEl = fld(this, 'ano_fabricacao');
      if (!anoFabEl.value) { showErr(anoFabEl, 'Ano de fabricação é obrigatório.'); ok = false; }
      else if (+anoFabEl.value < 1950 || +anoFabEl.value > currYear + 1) {
        showErr(anoFabEl, 'Ano de fabricação inválido (1950–' + (currYear + 1) + ').'); ok = false;
      } else clearErr(anoFabEl);

      const anoModEl = fld(this, 'ano_modelo');
      if (!anoModEl.value) { showErr(anoModEl, 'Ano do modelo é obrigatório.'); ok = false; }
      else if (+anoModEl.value < 1950 || +anoModEl.value > currYear + 2) {
        showErr(anoModEl, 'Ano do modelo inválido (1950–' + (currYear + 2) + ').'); ok = false;
      } else clearErr(anoModEl);

      const placaEl = fld(this, 'placa');
      if (!placaEl.value.trim()) { showErr(placaEl, 'Placa é obrigatória.'); ok = false; }
      else if (!validPlaca(placaEl.value.trim())) {
        showErr(placaEl, 'Placa inválida. Ex: ABC1234 ou ABC1D23.'); ok = false;
      } else clearErr(placaEl);

      const chassiEl = fld(this, 'chassi');
      if (!chassiEl.value.trim()) { showErr(chassiEl, 'Chassi é obrigatório.'); ok = false; }
      else if (chassiEl.value.replace(/\s/g, '').length !== 17) {
        showErr(chassiEl, 'Chassi deve ter exatamente 17 caracteres.'); ok = false;
      } else clearErr(chassiEl);

      const diariaEl = fld(this, 'valor_diaria');
      if (!diariaEl.value) { showErr(diariaEl, 'Valor da diária é obrigatório.'); ok = false; }
      else if (parseFloat(diariaEl.value) <= 0) { showErr(diariaEl, 'Valor da diária deve ser maior que zero.'); ok = false; }
      else clearErr(diariaEl);

      if (!chkNonNeg(this, 'quilometragem', 'Quilometragem')) ok = false;
      if (!chkRange(this, 'num_portas', 2, 6, 'Número de portas')) ok = false;
      if (!chkRange(this, 'capacidade_passageiros', 1, 20, 'Capacidade de passageiros')) ok = false;
      if (!chkMaxlen(this, 'cor', 50, 'Cor')) ok = false;
      if (!chkEnum(this, 'status', ['disponivel','locado','manutencao','inativo'], 'Status')) ok = false;
      if (!chkEnum(this, 'combustivel', ['flex','gasolina','etanol','diesel','eletrico','hibrido'], 'Combustível')) ok = false;

      if (!ok) e.preventDefault();
    });
  }

  // ── form: locacao ────────────────────────────────────────────────
  const frmLocacao = document.getElementById('frmLocacao');
  if (frmLocacao) {
    frmLocacao.addEventListener('submit', function (e) {
      clearAll(this); let ok = true;
      if (!chkSelect(this, 'id_cliente', 'o cliente')) ok = false;
      if (!chkSelect(this, 'id_veiculo', 'o veículo')) ok = false;
      if (!chkSelect(this, 'id_funcionario', 'o funcionário')) ok = false;
      if (!chkSelect(this, 'id_agencia_retirada', 'a agência de retirada')) ok = false;
      if (!chkSelect(this, 'id_agencia_devolucao', 'a agência de devolução')) ok = false;
      if (!chkEnum(this, 'status', ['aberta','encerrada','cancelada'], 'Status')) ok = false;

      const retEl = fld(this, 'data_retirada');
      const devEl = fld(this, 'data_devolucao_prevista');

      if (!retEl.value) { showErr(retEl, 'Data de retirada é obrigatória.'); ok = false; }
      else clearErr(retEl);

      if (!devEl.value) { showErr(devEl, 'Data de devolução prevista é obrigatória.'); ok = false; }
      else if (retEl.value && devEl.value < retEl.value) {
        showErr(devEl, 'Devolução prevista deve ser igual ou posterior à retirada.'); ok = false;
      } else clearErr(devEl);

      const devRealEl = fld(this, 'data_devolucao_real');
      if (devRealEl && devRealEl.value && retEl.value && devRealEl.value < retEl.value) {
        showErr(devRealEl, 'Devolução real deve ser igual ou posterior à data de retirada.'); ok = false;
      } else if (devRealEl) clearErr(devRealEl);

      const diariaEl = fld(this, 'valor_diaria');
      if (!diariaEl.value) { showErr(diariaEl, 'Valor da diária é obrigatório.'); ok = false; }
      else if (parseFloat(diariaEl.value) <= 0) { showErr(diariaEl, 'Valor da diária deve ser maior que zero.'); ok = false; }
      else clearErr(diariaEl);

      if (!ok) e.preventDefault();
    });
  }

  // ── form: pagamento ──────────────────────────────────────────────
  const frmPagamento = document.getElementById('frmPagamento');
  if (frmPagamento) {
    frmPagamento.addEventListener('submit', function (e) {
      clearAll(this); let ok = true;
      if (!chkSelect(this, 'id_locacao', 'a locação')) ok = false;

      const valEl = fld(this, 'valor');
      if (!valEl.value) { showErr(valEl, 'Valor é obrigatório.'); ok = false; }
      else if (parseFloat(valEl.value) <= 0) { showErr(valEl, 'Valor deve ser maior que zero.'); ok = false; }
      else clearErr(valEl);

      if (!chkEnum(this, 'forma_pagamento', ['credito','debito','pix','dinheiro','boleto'], 'Forma de pagamento')) ok = false;

      const parcelasEl = fld(this, 'parcelas');
      if (parcelasEl && parcelasEl.value !== '' && parseInt(parcelasEl.value) < 1) {
        showErr(parcelasEl, 'Mínimo 1 parcela.'); ok = false;
      } else if (parcelasEl) clearErr(parcelasEl);

      if (!chkEnum(this, 'status', ['pendente','aprovado','recusado','estornado'], 'Status')) ok = false;

      const codEl = fld(this, 'codigo_transacao');
      if (codEl) { if (codEl.value.trim().length > 100) { showErr(codEl, 'Código de transação máx. 100 caracteres.'); ok = false; } else clearErr(codEl); }

      const dtPagEl = fld(this, 'data_pagamento');
      if (dtPagEl) clearErr(dtPagEl);

      if (!ok) e.preventDefault();
    });
  }

  // ── form: seguro ─────────────────────────────────────────────────
  const frmSeguro = document.getElementById('frmSeguro');
  if (frmSeguro) {
    frmSeguro.addEventListener('submit', function (e) {
      clearAll(this); let ok = true;
      if (!chkSelect(this, 'id_locacao', 'a locação')) ok = false;

      const tipoEl = fld(this, 'tipo');
      if (!tipoEl.value.trim()) { showErr(tipoEl, 'Tipo de seguro é obrigatório.'); ok = false; }
      else if (tipoEl.value.trim().length > 80) { showErr(tipoEl, 'Tipo máx. 80 caracteres.'); ok = false; }
      else clearErr(tipoEl);

      const apoliceEl = fld(this, 'apolice');
      if (apoliceEl) { if (apoliceEl.value.trim().length > 50) { showErr(apoliceEl, 'Nº apólice máx. 50 caracteres.'); ok = false; } else clearErr(apoliceEl); }

      if (!chkNonNeg(this, 'valor_franquia', 'Valor da franquia')) ok = false;
      if (!chkNonNeg(this, 'valor_diaria',   'Valor da diária'))   ok = false;

      const coberturaEl = fld(this, 'cobertura');
      if (coberturaEl) clearErr(coberturaEl);

      if (!ok) e.preventDefault();
    });
  }

  // ── form: avaria ─────────────────────────────────────────────────
  const frmAvaria = document.getElementById('frmAvaria');
  if (frmAvaria) {
    frmAvaria.addEventListener('submit', function (e) {
      clearAll(this); let ok = true;
      if (!chkSelect(this, 'id_locacao',    'a locação'))    ok = false;
      if (!chkSelect(this, 'id_funcionario', 'o funcionário')) ok = false;
      if (!chkEnum(this, 'status', ['pendente','em_reparo','concluida'], 'Status')) ok = false;
      if (!chkNonNeg(this, 'valor_reparo', 'Valor do reparo')) ok = false;
      if (!chkRequired(this, 'descricao', 'Descrição')) ok = false;
      if (!ok) e.preventDefault();
    });
  }

  // ── form: manutencao ─────────────────────────────────────────────
  const frmManutencao = document.getElementById('frmManutencao');
  if (frmManutencao) {
    frmManutencao.addEventListener('submit', function (e) {
      clearAll(this); let ok = true;
      if (!chkSelect(this, 'id_veiculo',    'o veículo'))    ok = false;
      if (!chkSelect(this, 'id_funcionario', 'o funcionário')) ok = false;
      if (!chkEnum(this, 'tipo', ['preventiva','corretiva','revisao'], 'Tipo')) ok = false;

      const entradaEl = fld(this, 'data_entrada');
      if (!entradaEl.value) { showErr(entradaEl, 'Data de entrada é obrigatória.'); ok = false; }
      else clearErr(entradaEl);

      const saidaEl = fld(this, 'data_saida');
      if (saidaEl && saidaEl.value && entradaEl.value && saidaEl.value < entradaEl.value) {
        showErr(saidaEl, 'Data de saída deve ser igual ou posterior à data de entrada.'); ok = false;
      } else if (saidaEl) clearErr(saidaEl);

      const kmEl = fld(this, 'quilometragem_entrada');
      if (kmEl.value === '' || kmEl.value === null) { showErr(kmEl, 'Quilometragem é obrigatória.'); ok = false; }
      else if (parseInt(kmEl.value) < 0) { showErr(kmEl, 'Quilometragem não pode ser negativa.'); ok = false; }
      else clearErr(kmEl);

      if (!chkNonNeg(this, 'custo', 'Custo')) ok = false;
      if (!chkEnum(this, 'status', ['aberta','em_andamento','concluida'], 'Status')) ok = false;

      const descEl = fld(this, 'descricao');
      if (descEl) clearErr(descEl);

      if (!ok) e.preventDefault();
    });
  }

})();

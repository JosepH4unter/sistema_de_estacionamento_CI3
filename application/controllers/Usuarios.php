<?php

defined('BASEPATH') or exit('Ação não permitida'); //aqui e para que caso uma pessoa tente acessar o controlador de forma direta

class Usuarios extends CI_Controller
{

    public function index()
    {

        $data = array(
            'titulo' => 'Usuários Cadastrados',
            'subtitulo' => 'Chegou a hora de listar todos os usuários cadastrados no Banco de dados',

            'styles' => array( //um array que armazena os estilos
                'plugins/datatables.net-bs4/css/dataTables.bootstrap4.min.css',
            ),
            'scripts' => array(
                'plugins/datatables.net/js/jquery.dataTables.min.js',
                'plugins/datatables.net-bs4/js/dataTables.bootstrap4.min.js',
                'plugins/datatables.net/js/estacionamento.js',
            ),

            'usuarios' => $this->ion_auth->users()->result(), //aqui esta retornando os usuarios do BD
        );

        // echo '<pre>';
        // print_r($data['usuarios']);
        // exit;
        // echo '</pre>';

        $this->load->view('layout/header', $data); //aqui estamos mandando para a view o $data
        $this->load->view('usuarios/index');
        $this->load->view('layout/footer');
    }

    public function core($usuario_id = NULL)
    {

        if (!$usuario_id) {

            exit('Pode cadastrar um novo usuário');
            //Cadastro de novo usuario
        } else {
            //editar usuário
            if (!$this->ion_auth->user($usuario_id)->row()) {
                exit('Usuário não existe');
            } else {

                $this->form_validation->set_rules('first_name', 'Nome', 'trim|required|min_length[5]|max_length[20]'); //se o primeiro valor nao for valido o Nome ira no lugar, depois vem as validacao o trim apaga espaçoes no inicio e no fim, ele e obrigatorio, minimo de 5 maximo de 20 caracter
                $this->form_validation->set_rules('last_name', 'Sobrenome', 'trim|required|min_length[5]|max_length[30]');
                // $this->form_validation->set_rules('username', 'Usuário', 'trim|required|min_length[5]|max_length[30]');
                $this->form_validation->set_rules('email', 'E-mail', 'trim|valid_email|min_length[5]|max_length[200]');
                $this->form_validation->set_rules('password', 'Senha', 'trim|min_length[8]');
                $this->form_validation->set_rules('confirmacao', 'Confirmação', 'trim|matches[password]');

                if ($this->form_validation->run()) {
                    echo '<pre>';
                    print_r($this->input->post());
                    echo '</pre>';
                } else {
                    //Editar usuário
                    $data = array(
                        'titulo' => 'Editar Usuários',
                        'subtitulo' => 'Chegou a hora de editar o usuário',
                        'icone_view' => 'ik ik-user', //aqui estamos colocando dinamicamente o icone
                        'usuario' => $this->ion_auth->user($usuario_id)->row(), //busco os dados e armazeno na variavel usuario
                        'perfil_usuario' => $this->ion_auth->get_users_groups($usuario_id)->row(),
                    );

                    // echo '<pre>';
                    // print_r($data['perfil_usuario']);
                    // exit;
                    // echo '</pre>';

                    $this->load->view('layout/header', $data); //aqui estamos mandando para a view o $data
                    $this->load->view('usuarios/core');
                    $this->load->view('layout/footer');
                }
            }
        }
    }
}

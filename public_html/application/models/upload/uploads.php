<?php

class Uploads extends CI_Model {
	
	var $original_path;
	
	//initialize the path where you want to save your images
	function __construct(){
		parent::__construct();
		//return the full path of the directory
		//make sure these directories have read and write permessions
		$this->original_path = realpath(APPPATH.'/third_party/upload');
	}

	//upload logo para desenvolver
    function do_upload($data=null,$file=null)
	{
		$config['upload_path'] = $this->original_path.'/logo';
		$config["allowed_types"] ="*";
		$config['max_size']	= '50000';
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload($file))
		{
			//$this->session->set_flashdata('message', $this->upload->display_errors());
			return false;
		}
		
		$data = array(
		   'id_logos' => $data['id_logos_upl']
		   ,'logo_url' => str_replace(' ','_',$_FILES['upl']['name'])
		   ,'nivel_acesso' => $data['nivel_acesso']
		);
		
		$this->db->insert('logos_media', $data); 
		
		if($this->db->affected_rows())
		{
			return true;
		}else{
			return false;
		}
	}
	
	//upload do logo ja aprovado
	function do_upload_sites_logo($data=null,$file=null)
	{
		$config['upload_path'] = $this->original_path.'/sites_logo';
		$config["allowed_types"] ="*";
		$config['max_size']	= '50000';
		//$config['max_width']  = '1024';
		//$config['max_height']  = '768';

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload($file))
		{
			//$this->session->set_flashdata('message', $this->upload->display_errors());
			return false;
		}
		
		$data = array(
		   'id_site' => $data['id_sites_upl']
		   ,'logo_url' => str_replace(' ','_',$_FILES['upl_sites_logo']['name'])
		);
		
		$this->db->set('data_entrada', 'NOW()', FALSE); 
		
		$this->db->insert('sites_logo', $data); 
		
		if($this->db->affected_rows())
		{
			return true;
		}else{
			return false;
		}
	}
	
	function do_upload_photo_profile_company($data=null, $file=null)
	{
		$this->load->library('image_lib');
		
		//original_image
		$config = array(
			'allowed_types'     => $data['ext'], //only accept these file types
			'max_size'          => 2048, //2MB max
			'upload_path'       => $this->original_path.'/company' //upload directory
		);
		
		$this->load->library('upload', $config);
		$image_data = $this->upload->do_upload($file); //upload the image
 
		//resize original
		$config = array(
			'source_image'      => $this->upload->upload_path.$this->upload->file_name, //path to the uploaded image
			'new_image'         => $this->original_path.'/company/original', //path to
			'maintain_ratio'    => true,
			'width'             => 600,
			'height'            => 400
		);

		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		//rsize thumb
		$config = array(
			'source_image'      => $this->upload->upload_path.$this->upload->file_name, //path to the uploaded image
			'new_image'         => $this->original_path.'/company/thumb', //path to
			'maintain_ratio'    => true,
			'width'             => 128,
			'height'            => 128
		);

		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		if($this->image_lib->resize())
		{	
			$file_name = $this->upload->file_name;
			
			$this->db
				->select('id, url_media')
				->from('empresas_media') 
				->where('id_empresa', $data['id_empresa']); 
			
			$query = $this->db->get(); 
			
			//possui registro - apenas altera a foto e remove a antiga do server
			if($query->num_rows()>0)
			{
				$result = $query->result();
				
				if($result[0]->url_media <> 'icon-default.png')
				{
					//apago a imagem se ela existe e nao for default
					if(is_file($this->original_path.'/company/original/'.stripslashes(trim($result[0]->url_media))))
					{
						unlink($this->original_path.'/company/original/'.stripslashes(trim($result[0]->url_media)));
						unlink($this->original_path.'/company/thumb/'.stripslashes(trim($result[0]->url_media)));
					}
				}
				
				$data_update = array(
					'url_media' => str_replace(' ','_',$_FILES['upl-photo-company']['name'])
				);
				
				$this->db->update('empresas_media', $data_update, array('id_empresa'=>$data['id_empresa'])); 
				
				//die($this->db->last_query());
			
			//nao tem registro - insere um novo
			}else{
			
				$data_insert = array(
				   'id_empresa' => $data['id_empresa']
				   ,'url_media' => stripslashes(trim($_FILES['upl-photo-company']['name']))
				);
				
				$this->db->insert('empresas_media', $data_insert); 
				
				//die($this->db->last_query());
			}
			
			if($this->db->affected_rows())
			{
				//removo a default da raiz da pasta upload
				if(is_file($this->original_path.'/company/'.$file_name))
				{
					unlink($this->original_path.'/company/'.$file_name);
				}
				return true;
			}else{
				return false;
			}
		}else{
			die($this->image_lib->display_errors('', ''));
		}	
	}
	
	function do_upload_photo_profile_socio($data=null, $file=null)
	{
		$this->load->library('image_lib');
		
		//original_image
		$config = array(
			'allowed_types'     => $data['ext'], //only accept these file types
			'max_size'          => 2048, //2MB max
			'upload_path'       => $this->original_path.'/socios' //upload directory
		);
		
		$this->load->library('upload', $config);
		$image_data = $this->upload->do_upload($file); //upload the image
 
		//resize original
		$config = array(
			'source_image'      => $this->upload->upload_path.$this->upload->file_name, //path to the uploaded image
			'new_image'         => $this->original_path.'/socios/original', //path to
			'maintain_ratio'    => true,
			'width'             => 600,
			'height'            => 400
		);

		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		//rsize thumb
		$config = array(
			'source_image'      => $this->upload->upload_path.$this->upload->file_name, //path to the uploaded image
			'new_image'         => $this->original_path.'/socios/thumb', //path to
			'maintain_ratio'    => true,
			'width'             => 128,
			'height'            => 128
		);

		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		if($this->image_lib->resize())
		{	
			$file_name = $this->upload->file_name;
			
			$this->db
				->select('id, url_media')
				->from('socios_media') 
				->where('id_socio', $data['id_socio']); 
			
			$query = $this->db->get(); 
			
			//possui registro - apenas altera a foto e remove a antiga do server
			if($query->num_rows()>0)
			{
				$result = $query->result();
				
				if($result[0]->url_media <> 'icon-default.png')
				{
					//apago a imagem se ela existe e nao for default
					if(is_file($this->original_path.'/socios/original/'.stripslashes(trim($result[0]->url_media))))
					{
						unlink($this->original_path.'/socios/original/'.stripslashes(trim($result[0]->url_media)));
						unlink($this->original_path.'/socios/thumb/'.stripslashes(trim($result[0]->url_media)));
					}
				}
				
				$data_update = array(
					'url_media' => str_replace(' ','_',$_FILES['upl-photo-socio']['name'])
				);
				
				$this->db->update('socios_media', $data_update, array('id_socio'=>$data['id_socio'])); 
				
			//nao tem registro - insere um novo
			}else{
			
				$data_insert = array(
				   'id_socio' => $data['id_socio']
				   ,'url_media' => stripslashes(trim($_FILES['upl-photo-socio']['name']))
				);
				
				$this->db->insert('socios_media', $data_insert); 
			}
			
			if($this->db->affected_rows())
			{
				//removo a default da raiz da pasta upload
				if(is_file($this->original_path.'/socios/'.$file_name))
				{
					unlink($this->original_path.'/socios/'.$file_name);
				}
				return true;
			}else{
				return false;
			}
		}else{
			die($this->image_lib->display_errors('', ''));
		}	
	}
	
	function do_upload_photo_profile_funcionario($data=null, $file=null)
	{
		$table = 'funcionarios_media';
		$folder = 'funcionarios';
		
		$this->load->library('image_lib');
		
		//original_image
		$config = array(
			'allowed_types'     => $data['ext'], //only accept these file types
			'max_size'          => 2048, //2MB max
			'upload_path'       => $this->original_path.'/'.$folder //upload directory
		);
		
		$this->load->library('upload', $config);
		$image_data = $this->upload->do_upload($file); //upload the image
 
		//resize original
		$config = array(
			'source_image'      => $this->upload->upload_path.$this->upload->file_name, //path to the uploaded image
			'new_image'         => $this->original_path.'/'.$folder.'/original', //path to
			'maintain_ratio'    => true,
			'width'             => 600,
			'height'            => 400
		);

		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		//rsize thumb
		$config = array(
			'source_image'      => $this->upload->upload_path.$this->upload->file_name, //path to the uploaded image
			'new_image'         => $this->original_path.'/'.$folder.'/thumb', //path to
			'maintain_ratio'    => true,
			'width'             => 128,
			'height'            => 128
		);

		$this->image_lib->initialize($config);
		$this->image_lib->resize();
		
		if($this->image_lib->resize())
		{	
			$file_name = $this->upload->file_name;
			
			$this->db
				->select('id, url_media')
				->from("$table") 
				->where('id_funcionario', $data['id_funcionario']); 
			
			$query = $this->db->get(); 
			
			//possui registro - apenas altera a foto e remove a antiga do server
			if($query->num_rows()>0)
			{
				$result = $query->result();
				
				if($result[0]->url_media <> 'icon-default.png')
				{
					//apago a imagem se ela existe e nao for default
					if(is_file($this->original_path.'/'.$folder.'/original/'.stripslashes(trim($result[0]->url_media))))
					{
						unlink($this->original_path.'/'.$folder.'/original/'.stripslashes(trim($result[0]->url_media)));
						unlink($this->original_path.'/'.$folder.'/thumb/'.stripslashes(trim($result[0]->url_media)));
					}
				}
				
				$data_update = array(
					'url_media' => str_replace(' ','_',$_FILES['upl-photo-funcionario']['name'])
				);
				
				$this->db->update("$table", $data_update, array('id_funcionario'=>$data['id_funcionario'])); 
				
			//nao tem registro - insere um novo
			}else{
			
				$data_insert = array(
				   'id_funcionario' => $data['id_funcionario']
				   ,'url_media' => stripslashes(trim($_FILES['upl-photo-funcionario']['name']))
				);
				
				$this->db->insert("$table", $data_insert); 
			}
			
			if($this->db->affected_rows())
			{
				//removo a default da raiz da pasta upload
				if(is_file($this->original_path.'/'.$folder.'/'.$file_name))
				{
					unlink($this->original_path.'/'.$folder.'/'.$file_name);
				}
				return true;
			}else{
				return false;
			}
		}else{
			die($this->image_lib->display_errors('', ''));
		}	
	}
	
	// envia informacoes de css e scripts para upload
	public function getDependenciesUpload()
	{
		$data['css'][] = 'style.css';
		
		$data['script'][] = 'jquery.knob.js';
		$data['script'][] = 'jquery.ui.widget.js';
		$data['script'][] = 'jquery.iframe-transport.js';
		$data['script'][] = 'jquery.fileupload.js';
		$data['script'][] = 'script.js';
		
		foreach($data['css'] AS $key=>$val)
		{
			echo "<link href='".site_url()."application/third_party/plugins/uploadphp/assets/css/$val' rel='stylesheet' />";
		}
		foreach($data['script'] AS $key=>$val)
		{
			echo "<script type='text/javascript' src='".site_url()."application/third_party/plugins/uploadphp/assets/js/$val' language='Javascript' ></script>";
		}
	}
	
	// retorna o tipo do arquivo
	public function returnTypeArchive($ext=null, $path=null, $media_url=null)
	{
		if(!$ext)
			return false;
			
		$icon_ext = $class_download = $href_download = null;
		
		switch($ext)
		{
			case 'jpg':
				$icon_ext = '
					<a href="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" class="lightbox">
						<img src="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" alt="" class="img-media">
					</a>';
				$class_download = 'icon-download-ex';
				$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;
			break;
			case 'jpeg':
				$icon_ext = '
					<a href="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" class="lightbox">
						<img src="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" alt="" class="img-media">
					</a>';
				$class_download = 'icon-download-ex';
				$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;
			break;
			case 'JPG':
				$icon_ext = '
					<a href="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" class="lightbox">
						<img src="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" alt="" class="img-media">
					</a>';
				$class_download = 'icon-download-ex';
				$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;
			break;
			case 'txt':$icon_ext = '<i class="icon-file-word"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'doc':$icon_ext = '<i class="icon-file-word"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'docx':$icon_ext = '<i class="icon-file-word"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'xls':$icon_ext = '<i class="icon-file-excel"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'xlsx':$icon_ext = '<i class="icon-file-excel"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'csv':$icon_ext = '<i class="icon-file-excel"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'zip':$icon_ext = '<i class="icon-file-zip"></i>';
				$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;
			break;
			case 'rar':$icon_ext = '<i class="icon-file-zip"></i>';
				$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;
			break;
			case 'ppt':$icon_ext = '<i class="icon-file-powerpoint"></i>';
				$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;
				$class_download = 'icon-download-ex';
			break;
			case 'pptx':$icon_ext = '<i class="icon-file-powerpoint"></i>';
				$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;
				$class_download = 'icon-download-ex';
			break;
			case 'png':$icon_ext = '
				<a href="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" class="lightbox">
					<img src="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" alt="" class="img-media">
				</a>';
				$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;
				$class_download = 'icon-download-ex';
			break;
			case 'PNG':$icon_ext = '
				<a href="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" class="lightbox">
					<img src="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" alt="" class="img-media">
				</a>';
				$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;
				$class_download = 'icon-download-ex';
			break;
			case 'gif':$icon_ext = '
				<a href="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" class="lightbox">
					<img src="'.site_url().'application/third_party/upload/'.$path.'/'.$media_url.'" alt="" class="img-media">
				</a>';
				$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;
				$class_download = 'icon-download-ex';
			break;
			case 'bmp':$icon_ext = '<i class="icon-image5"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'ai':$icon_ext = '<i class="icon-image5"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'cdr':$icon_ext = '<i class="icon-image5"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'tiff':$icon_ext = '<i class="icon-image5"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'psd':$icon_ext = '<i class="icon-image5"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'mp3':$icon_ext = '<i class="icon-music3"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'wav':$icon_ext = '<i class="icon-music3"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			case 'pdf':$icon_ext = '<i class="icon-file-pdf"></i>';$href_download = site_url().'application/third_party/upload/domyupload.php?file='.$path.'/'.$media_url;break;
			default:$icon_ext = '<i class="icon-file7"></i>';break;
		}
		
		$result = Array(
			'icon_ext' => $icon_ext,
			'class_download' => $class_download,
			'href_download' => $href_download
		);
		
		return $result;		
	}
	
}


/* End of file login.php */
/* Location: ./application/models/login.php */
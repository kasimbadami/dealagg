<?php
class Rediff extends Parsing{
	public $_code = 'Rediff';

	public function getAllowedCategory(){
		return array(Category::BOOKS,Category::MOBILE,Category::MOBILE_ACC);
	}

	public function getWebsiteUrl(){
		return 'rediff.com';
	}
	public function getSearchURL($query,$category = false,$subcat=false){
		if($category == Category::BOOKS){
			return "http://books.rediff.com/search/$query?&output=xml&src=search_$query";
		}else if($category == Category::MOBILE){
			$query = urldecode($query);
			return "http://shopping.rediff.com/productv2/$query/cat-mobile phones & accessories|mobile accessories";
		}else if($category == Category::CAMERA){
			if($subcat == Category::NOT_SURE){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras & optics|digital cameras";
			}else if($subcat == Category::CAM_DIGITAL_CAMERA){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras & optics|digital cameras";
			}else if($subcat == Category::CAM_DIGITAL_SLR){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras & optics|digital slr cameras";
			}else if($subcat == Category::CAM_CAMCORDER){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras & optics|camcorders";
			}else if($subcat == Category::CAM_MIRRORLESS){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras & optics|digital cameras";
			}else{
				return '';
			}
		}else if($category == Category::CAMERA_ACC){
			if($subcat == Category::NOT_SURE){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras & optics|camera accessories";
			}else if($subcat == Category::CAM_ACC_ADAPTER_CHARGES){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras+%26amp%3B+optics%7Ccamera+accessories%7Cbatteries+%26amp%3B+chargers?ref_src=topnav_Cameras";
			}else if($subcat == Category::CAM_ACC_BAGS){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras+%26amp%3B+optics%7Ccamera+accessories%7Ccamera+bags?ref_src=topnav_Cameras";
			}else if($subcat == Category::CAM_ACC_BATTERY){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras+%26amp%3B+optics%7Ccamera+accessories%7Cbatteries+%26amp%3B+chargers?ref_src=topnav_Cameras";
			}else if($subcat == Category::CAM_ACC_FLASH_LIGHTS){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras+%26amp%3B+optics%7Ccamera+accessories%7Cother+camera+accessories?ref_src=topnav_Cameras";
			}else if($subcat == Category::CAM_ACC_LENSEFILTER){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras+%26amp%3B+optics%7Ccamera+accessories%7Ccamera+lenses?ref_src=topnav_Cameras";
			}else if($subcat == Category::CAM_ACC_LENSES){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras+%26amp%3B+optics%7Ccamera+accessories%7Ccamera+lenses?ref_src=topnav_Cameras";
			}else if($subcat == Category::CAM_ACC_MEMORY_AND_STORAGE){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras+%26amp%3B+optics%7Ccamera+accessories%7Cmemory+cards?ref_src=topnav_Cameras";
			}else if($subcat == Category::CAM_ACC_OTHER_ACC){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras+%26amp%3B+optics%7Ccamera+accessories%7Cother+camera+accessories?ref_src=topnav_Cameras";
			}else if($subcat == Category::CAM_ACC_SCREEN_PROTECTOR){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras+%26amp%3B+optics%7Ccamera+accessories%7Cother+camera+accessories?ref_src=topnav_Cameras";
			}else if($subcat == Category::CAM_ACC_TRIPODS){
				return "http://shopping.rediff.com/productv2/$query/cat-cameras+%26amp%3B+optics%7Ccamera+accessories%7Ctripods?ref_src=topnav_Cameras";
			}else{
				return '';
			}
		}else{
			return "http://shopping.rediff.com/shopping/index.html#!".urldecode($query);
		}
	}
	public function getLogo(){
		return "http://books.rediff.com/booksrediff/pix/rediff.png";
	}
	public function getData($html,$query,$category,$subcat){
		if($category == Category::BOOKS){
			$data = array();

			$xmlObj = new SimpleXMLElement($html);
			$match = $xmlObj->QueryMatch->total;
			if($match > 0){
				foreach($xmlObj->QueryMatch->book as $book){
					$name = $book->title."";
					$isbn = $book->isbn."";
					$author = $book->author.'';
					$image = $book->imagesmall.'';
					$disc_price = $book->domesticwebprice.'';
					$url = $book->book_url.'';
					$shipping = $book->despatchleadtime.' Working Days';
					$cat = '';
					$stock = 0;
					$offer = '';
					$data[] = array(
							'name'=>$name,
							'image'=>$image,
							'disc_price'=>$disc_price,
							'url'=>$url,
							'website'=>$this->getCode(),
							'offer'=>$offer,
							'shipping'=>$shipping,
							'stock'=>$stock,
							'author' => $author,
							'isbn' => $isbn,
							'cat' => $cat
					);
				}
			}
			$data = $this->cleanData($data, $query);
			$data = $this->bestMatchData($data, $query,$category);
			return $data;
		}else{
			$data = array();
			phpQuery::newDocumentHTML($html);
			if(sizeof(pq('.div_grid_display_big')) > 0){
				foreach(pq('.div_grid_display_big') as $div){
					$image = pq($div)->find('.mitemimg_h4_big:first')->children('a')->html();
					$url = pq($div)->find('.mitemimg_h4_big:first')->children('a')->attr('href');
					$name = pq($div)->find('.mitemname_h4:first')->children('a')->html();
					$disc_price = pq($div)->children('div')->children('div')->children('div:last')->children('span')->html();
					$offer = '';
					$shipping = '';
					$stock = 0;
					$author = '';
					$data[] = array(
							'name'=>$name,
							'image'=>$image,
							'disc_price'=>$disc_price,
							'url'=>$url,
							'website'=>$this->getCode(),
							'offer'=>$offer,
							'shipping'=>$shipping,
							'stock'=>$stock,
							'author' => $author,
							'cat' => ''
					);
				}
			}
			$data2 = array();
			foreach($data as $row){
				$html = $row['image'];
				$html .= '</img>';
				phpQuery::newDocumentHTML($html);
				$img = pq('img')->attr('src');
				$row['image'] = $img;
				$data2[] = $row;
			}
			$data2 = $this->cleanData($data2, $query);
			$data2 = $this->bestMatchData($data2, $query,$category,$subcat);
			return $data2;
		}
	}
}
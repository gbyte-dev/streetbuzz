<?php
class Mpdf_model extends MY_Model {

    public function __construct() {
        parent::__construct();
    }
   
    /**
     * used for validate user aut token
     * @param string $access_token
     * @return array
     */
    public function getAdsRecords($adid,$fromdate,$todate) {
      /* $this->db->select("p.id,pv.view_date,SUM(pv.cnt) as cnt");
        $this->db->from('ads_info ad ');
        $this->db->join('ads_tags at', 'at.ad_id = ad.id');
        $this->db->join('posts p', 'p.user_id = at.user_id');
        $this->db->join('post_views_day_wise pv', 'p.id = pv.post_id');
        $this->db->where('(ad.id="'.$adid.'" AND pv.view_date >= "'.$fromdate.'" AND pv.view_date <= "'.$todate.'")');
        $this->db->group_by("pv.view_date"); // Produces: GROUP BY title
        $sql = $this->db->get();

               
        $result = $sql->row_array(); 
        print_r($result);exit;
        return ($result) ? $result : array(); */
        $query2 = $this->db->query('select p.id,pv.view_date,SUM(pv.cnt) as cnt,SUM(aci.cnt) as clickcnt,ad.customer_name,ad.contact_number,ad.sales_person from ads_info as ad inner join ads_tags as at on at.ad_id= ad.id 
inner join posts as p ON p.user_id = at.user_id
inner join  post_views_day_wise as pv ON p.id = pv.post_id
left join   ads_click_info as aci ON p.id = aci.post_id


where ad.id="'.$adid.'" AND pv.view_date >= "'.$fromdate.'" AND pv.view_date <= "'.$todate.'" group by pv.view_date  order by pv.view_date desc'
        );
        $results = $query2->result_array();  
        return $results;
         

    }
    
}

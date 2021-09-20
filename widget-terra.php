<?php



/*
Plugin Name: ARIEL 	api finance wiget
Description: get values fro the api.
Plugin URI:  https://arielbarrios.com
Author:      Ariel Barrios
Version:     1.0
*/



class My_Widget extends WP_Widget {

    function __construct() {

        parent::__construct(
            'Ariel-widget-id',  // Base ID
            'Ariel-widget-Text'   // Name
        );

        add_action( 'widgets_init', function() {
            register_widget( 'My_Widget' );
        });

    }

    public $args = array(
        'before_title'  => '<h4 class="widgettitle">',
        'after_title'   => '</h4>',
        'before_widget' => '<div class="widget-wrap">',
        'after_widget'  => '</div></div>'
    );




    public function widget( $args, $instance ) {

        echo $args['before_widget'];


        if ( ! empty( $instance['title'] ) ) {
            echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'];
        }


        echo '<div class="textwidget">';

        echo esc_html__( $instance['text'], 'text_domain' );
        ?>



		<div class="container my-5">
					<div class="row ">
						<div class="col d-flex  align-items-center">
							<label for="inputState" class="form-label col-3">Select Currency Pair</label>
							<select onchange="submitcurrency(this.value,5)" id="inputState" class="form-select">
								<option value="USD/CAD" selected>USD/CAD</option>
								<option value="USD/EUR" >USD/EUR</option>
								<option value="USD/AUD" >USD/AUD</option>
								<option value="EUR/AUD" >EUR/AUD</option>
								<option value="EUR/JPY" >EUR/JPY</option>
							</select>
						</div>
				</div>
		</div>



		<div class="container my-5">
			<div class="row text-center  align-items-center align-space-between justify-content-center border py-3 rounded  bg-light">
				<div class="col-5 "><h2 class="baseCurrecncy-class">Base Currecncy</h2></div>
				<div class="col-1  display-6"> = </div>
				<div class="col-5 "><h2 class="quoteCurrency-class">Quote Currency</h2></div>
			</div>
		</div>


		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
		<script src="https://cdn.jsdelivr.net/npm/chart.js@3.5.1/dist/chart.min.js" integrity="sha256-bC3LCZCwKeehY6T4fFi9VfOU0gztUa+S4cnkIhVPZ5E=" crossorigin="anonymous"></script>


		<script>


		$(document).ready(function() {

			submitcurrency($('#inputState').val(),5)
      		var timer = setInterval(function(){ submitcurrency($('#inputState').val(),5) }, 1000 * 60 * 60);



		})


		function active_menu(days) {

				$('.button_group .btn').removeClass('btn-secondary');

				$("button[data-days='" + days +"']").addClass('btn-secondary');
				// $($('.button_group .btn').attr('data-days')).addClass('btn-secondary');
		}

		</script>




		<div class="container my-5">
			<div class="row justify-content-center">
				<div class="col-6 my-0" >
					<canvas id="myChart" width="400" height="400"></canvas>
				</div>
			</div>
			<div class="row  ">
				<div class="col button_group d-flex  align-space-between justify-content-center">
					<button type="button"  onclick="submitcurrency($('#inputState').val(),1);active_menu(1)" data-days="1" class="btn border-0  btn-outline-secondary  btn-sm mx-1">Day</button>
					<button type="button" onclick="submitcurrency($('#inputState').val(),5);active_menu(5)" data-days="5" class="btn  border-0 btn-secondary  btn-sm mx-1">5 Days</button>
					<button type="button"  onclick="submitcurrency($('#inputState').val(),8);active_menu(8)" data-days="8" class="btn  border-0 btn-outline-secondary  btn-sm mx-1 ">8 Days</button>

				</div>
			</div>

		</div>
		<div class="container my-5">
			<div class="row">
				<div class="col">
					<h6 class="d-inline">Last update:  </h6> <small><i class="timeStamp"></i></small>
				</div>
			</div>
		</div>










		<script>

			const theapiKey="89225ded3fb447d5ef3e";

			let labels = [];

			let dataChart = {
			  labels: labels,
			  datasets: [{
			    label: '',
			    backgroundColor: 'rgb(255, 99, 132)',
			    borderColor: 'rgb(255, 99, 132)',
			    data: [],
			},

			{
			  label: '',
			  backgroundColor: 'rgb(99, 101, 255)',
			  borderColor: 'rgb(107, 99, 255)',
			  data: [],
			}

			]};

			const config = {
			  type: 'bar',
			  data: dataChart,
			  options: {}
			};

			// === include 'setup' then 'config' above ===
			var myChart = new Chart(
			  document.getElementById('myChart'),
			  config
			);






		var tehatavar;

		let get_year =(rest_year) =>{
			return new Date().getFullYear();
		}

		let get_month =(rest_month) =>{
			the_month = new Date().getMonth();
			//another uncitonality ti be adde here for more days than a month can contain
			the_month = the_month - rest_month;

			return  the_month < 10 ? "0" + the_month : the_month;
		}

		let get_day =(rest_day) =>{
			the_day =  new Date().getDate();

			//what if more day then month
			the_day = the_day - rest_day;
			return  the_day < 10 ? "0" + the_day : the_day;

		}

		let actualdate =  get_year(0) +'-'+get_month(0)+'-'+get_day(0);
		let actualdate_minus2 =  get_year(0) +'-'+get_month(2)+'-'+get_day(0);
		let daterequested_minus2 =  get_year(0) +'-'+get_month(2)+'-'+get_day(8);


		function submitcurrency(currenccyPair,timePeriod){

			active_menu(timePeriod);
			timePeriodDays =  get_year(0) +'-'+get_month(2)+'-'+get_day(timePeriod);
			currenrrancyArray = currenccyPair.split("/") ;

			baseCurrecncy = currenrrancyArray[0];
			quoteCurrency = currenrrancyArray[1];

			// var getvalue=$('#enterinputval').val();
			console.log( baseCurrecncy );
			console.log( quoteCurrency );

			$.get( "https://free.currconv.com/api/v7/convert?q="+baseCurrecncy+"_"+quoteCurrency+","+quoteCurrency+"_"+baseCurrecncy+"&compact=ultra&apiKey="+theapiKey+"", function( data ) {

				tehatavar = data;
				let baseCurrecncy_quoteCurrency = baseCurrecncy+'_'+quoteCurrency;
				let quoteCurrency_baseCurrecncy = quoteCurrency+'_'+baseCurrecncy;
			    console.log(tehatavar[baseCurrecncy+'_'+quoteCurrency]);
				$(".baseCurrecncy-class").text(baseCurrecncy+': '+tehatavar[baseCurrecncy_quoteCurrency]);
				$(".quoteCurrency-class").text(quoteCurrency+': '+tehatavar[quoteCurrency_baseCurrecncy]);
				$(".timeStamp").text(new Date($.now()));

			});



			$.get("https://free.currconv.com/api/v7/convert?apiKey="+theapiKey+"&q="+baseCurrecncy+"_"+quoteCurrency+","+quoteCurrency+"_"+baseCurrecncy+"&compact=ultra&date="+timePeriodDays+"&endDate="+actualdate_minus2, function(data) {
				timerangedata = data;
			    console.log(timePeriodDays);
			    console.log(actualdate_minus2);
			    console.log("https://free.currconv.com/api/v7/convert?apiKey="+theapiKey+"&q="+baseCurrecncy+"_"+quoteCurrency+","+quoteCurrency+"_"+baseCurrecncy+"&compact=ultra&date="+timePeriodDays+"&endDate="+actualdate_minus2);

				let days_amount = timePeriod; //i setup five days , but should be a dynamic setup
				let starting_day = get_day(timePeriod); // again but should be a dynamic setup

				let currency_label_by_day_baseCurrecncy =[];
				let currency_val_by_day_baseCurrecncy =[];


				let currency_label_by_day_quoteCurrency =[];
				let currency_val_by_day_quoteCurrency =[];

					for (var i = 0; i < days_amount; i++) {
						currency_val_by_day_baseCurrecncy.push(timerangedata[baseCurrecncy+'_'+quoteCurrency]['2021-06-'+(starting_day + i)]);
						currency_val_by_day_quoteCurrency.push(timerangedata[quoteCurrency+'_'+baseCurrecncy]['2021-06-'+(starting_day + i)]);
						currency_label_by_day_baseCurrecncy.push('2021-06-'+(starting_day + i)) ;
						// currency_label_by_quoteCurrency.push('2021-06-'+(starting_day + i)) ;

						dataChart.labels = currency_label_by_day_baseCurrecncy ;
						dataChart.datasets[0].data=currency_val_by_day_baseCurrecncy;
						dataChart.datasets[1].data=currency_val_by_day_quoteCurrency;
						dataChart.datasets[0].label =baseCurrecncy+'/'+quoteCurrency;
					}

				console.log(dataChart.labels);
				myChart.update();
			});
		}

		</script>





		<?php

        echo '</div>';

        echo $args['after_widget'];

    }
















    public function form( $instance ) {

        $title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'text_domain' );
        $text = ! empty( $instance['text'] ) ? $instance['text'] : esc_html__( '', 'text_domain' );
        ?>
        <p>
        <label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php echo esc_html__( 'Title:', 'text_domain' ); ?></label>
            <input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
        </p>
        <p>
            <label for="<?php echo esc_attr( $this->get_field_id( 'Text' ) ); ?>"><?php echo esc_html__( 'Text:', 'text_domain' ); ?></label>
            <textarea class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'text' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'text' ) ); ?>" type="text" cols="30" rows="10"><?php echo esc_attr( $text ); ?></textarea>
        </p>
        <?php

    }

    public function update( $new_instance, $old_instance ) {

        $instance = array();

        $instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
        $instance['text'] = ( !empty( $new_instance['text'] ) ) ? $new_instance['text'] : '';

        return $instance;
    }

}
$my_widget = new My_Widget();
?>

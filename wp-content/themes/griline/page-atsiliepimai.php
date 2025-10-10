<?php
/*
Template Name: Atsiliepimai
*/
get_header('page'); ?>

<style>
    .feedback {
        padding: 50px 0;
        background-color: #f8f9fa;
    }
    
    .feedback-wrapper {
        max-width: 800px;
        margin: 0 auto;
    }
    
    .card {
        border-radius: 10px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        border: none;
    }
    
    .card-body {
        padding: 30px;
    }
    
    .card-title {
        font-size: 28px;
        color: #333;
        margin-bottom: 25px;
        text-align: center;
        font-weight: 600;
    }
    
    .form-group {
        margin-bottom: 25px;
    }
    
    .form-group p {
        font-size: 18px;
        margin-bottom: 10px;
        color: #555;
    }
    
    .form-group p i {
        color: #ffc107;
        margin-right: 8px;
    }
    
    .rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: center;
        margin: 20px 0;
    }
    
    .rating input {
        display: none;
    }
    
    .rating label {
        cursor: pointer;
        font-size: 30px;
        padding: 5px;
        color: #ddd;
        transition: all 0.2s ease;
    }
    
    .rating label:hover,
    .rating label:hover ~ label,
    .rating input:checked ~ label {
        color: #ffc107;
    }
    
    .message-box {
        margin-top: 25px;
    }
    
    textarea {
        width: 100%;
        padding: 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        font-size: 16px;
        margin-bottom: 15px;
        transition: border-color 0.3s;
        resize: vertical;
    }
    
    textarea:focus {
        border-color: #0275d8;
        outline: none;
        box-shadow: 0 0 0 3px rgba(2, 117, 216, 0.2);
    }
    
    button[type="submit"] {
        background-color: #0275d8;
        color: white;
        border: none;
        padding: 12px 25px;
        border-radius: 6px;
        font-size: 16px;
        cursor: pointer;
        transition: background-color 0.3s;
        display: block;
        margin: 0 auto;
    }
    
    button[type="submit"]:hover {
        background-color: #025aa5;
    }
</style>

<div class="container-fluid feedback">
    <div class="container">
        <div class="feedback-wrapper">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Atsiliepimai</h5>
                    <form id="ratingForm">
                        <div class="form-group">
                            <p><i class="fas fa-star"></i>Įvertinkite mūsų paslaugas:</p>
                            <div class="rating">
                                <input type="radio" name="rating" id="star5" value="5"><label for="star5" title="5 stars"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" id="star4" value="4"><label for="star4" title="4 stars"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" id="star3" value="3"><label for="star3" title="3 stars"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" id="star2" value="2"><label for="star2" title="2 stars"><i class="fas fa-star"></i></label>
                                <input type="radio" name="rating" id="star1" value="1"><label for="star1" title="1 star"><i class="fas fa-star"></i></label>
                            </div>
                        </div>
                    </form>
                    <div class="message-box">
                        <form id="feedbackForm">
                            <textarea name="message" id="message" rows="4" placeholder="Jūsų atsiliepimas"></textarea>
                            <button type="submit">Siųsti</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/assets/js/reviews.js"></script>
<?php get_footer(); ?>

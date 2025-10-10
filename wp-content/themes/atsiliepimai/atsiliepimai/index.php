<?php
?><?php 
// ... existing PHP code ...
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Atsiliepimai</title>
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />
</head>
<body>
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
    <script src="../assets/js/script.js"></script>
</body>
</html>
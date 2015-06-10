<!-- Comments -->
<div id="div-share-details-comments" ng-controller="DetailsController" class="card">
    <div class="card-header" style="background-color: #3498db;">
        Commentaires
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="div-share-details-comments-list" ng-if="(comments.length > 0)">
                <!-- All comments -->
                <div ng-repeat="comment in comments">

                    <!-- Creator comment -->
                    <div ng-if="(shareUserExternalId === comment.user.external_id)" class="media">
                        <div class="media-body">
                            <blockquote class="blockquote-reverse">

                                <h4 class="media-heading">{{ comment.user.username }}</h4>

                                <p class="lead">{{ comment.message }}</p>

                                <footer>
                                    <span>{{ comment.moment_created_time_ago }}</span>
                                </footer>

                            </blockquote>
                        </div>
                        <div class="media-right">
                            <img class="comment-user-img img-circle img-thumbnail" ng-src="https://graph.facebook.com/v2.3/{{ comment.user.external_id }}/picture" />
                        </div>
                    </div>

                    <!-- Other user comment -->
                    <div ng-if="(shareUserExternalId !== comment.user.external_id)" class="media">
                        <div class="media-left">
                            <img class="comment-user-img img-circle img-thumbnail" ng-src="https://graph.facebook.com/v2.3/{{ comment.user.external_id }}/picture" />
                        </div>
                        <div class="media-body">
                            <blockquote class="blockquote-normal">

                                <h4 class="media-heading">{{ comment.user.username }}</h4>

                                <p class="lead">{{ comment.message }}</p>

                                <footer>
                                    <span>{{ comment.moment_created_time_ago }}</span>
                                </footer>

                            </blockquote>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <?php echo $this->element('pagination'); ?>
            </div>

            <!-- No comments -->
            <div ng-if="(comments.length == 0)">
                <p class="lead text-center">
                    Aucun commentaire
                </p>
            </div>
        </div>

        <?php if ($this->LocalUser->isAuthenticated($this)) : ?>

            <div class="col-md-10 div-share-details-comments-editor">
                <div style="padding-left: 15px;">
                    <textarea id="textarea-comment-add" class="form-control" rows="3"></textarea>
                </div>
            </div>
            <div class="col-md-2 div-share-details-comments-editor">
                <div style="padding-right: 15px;">
                    <!-- Send form -->
                    <?php
                        echo $this->Form->create('Comment', array(
                            'action' => 'add',
                            'id' => 'form-comment-add'
                        ));

                        echo $this->Form->hidden('shareId', array(
                            'value' => $share['share_id']
                        ));

                        echo $this->Form->hidden('message', array(
                            'value' => '',
                            'id' => 'hidden-comment-add-message'
                        ));

                        echo $this->Form->end();
                    ?>

                    <button id="btn-comment-add" type="submit" class="btn btn-primary">Envoyer</button>
                </div>
            </div>

        <?php else : ?>

            <div class="col-md-12 div-share-details-comments-editor">
                <div style="padding-left: 15px; padding-right: 15px;">
                    <div class="alert alert-info" role="alert">
                        <strong>Information :</strong> Vous devez être authentifié pour commenter.
                    </div>
                </div>
            </div>

        <?php endif; ?>
    </div>
</div>

<?php if ($this->LocalUser->isAuthenticated($this)) : ?>

<script>
    //Function used to send a comment
    function sendComment(message) {
        //Check message length
        if (message.length >= <?php echo SHARE_COMMENT_MESSAGE_MIN_LENGTH; ?>) {
            //Get the message and push it to the corresponding hidden input
            $('#hidden-comment-add-message').val(message);

            //And submit the form
            $('#form-comment-add').submit();
        } else {
            //Empty message
            toastr.warning('Veuillez saisir un message d\'au moins <?php echo SHARE_COMMENT_MESSAGE_MIN_LENGTH; ?> caractères.', 'Attention');
        }
    }

    //Method called when the user click on the comment send button
    $('#btn-comment-add').click(function () {
        //Get editor
        var editor = nicEditors.findEditor('textarea-comment-add');

        //And its message
        var message = editor.getContent();

        //Finally send the comment
        sendComment(message);
    });

    //On ready
    $(document).ready(function() {
        //Create editor
        new nicEditor({
            buttonList : ['bold','italic','underline', 'link', 'unlink']
        })
        .panelInstance('textarea-comment-add');

        //Initial empty content
        var editor = nicEditors.findEditor('textarea-comment-add');
        editor.setContent('');

    });
</script>

<?php endif; ?>
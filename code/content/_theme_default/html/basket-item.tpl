                            <tr class="basket_tr_{slot}">
                                <td class="image-td image hidden-sm hidden-xs">
                                    <a href="{url}">
                                        <img src="{image}" alt="" class="img-responsive">
                                    </a>
                                </td>
                                <td class="td-detail"><a href="{url}">{item}</a><span>{detail}</span></td>
                                <td class="td-sub">{cost}</td>
                                <td class="text-center remove-td">
                                    <a href="#" class="remove_basket" onclick="jQuery('.main-container').mMusicOps('remove','{slot}','basket');return false">
                                        <i class="fa fa-trash-o mm_red"></i>
                                    </a>
                                </td>
                            </tr>